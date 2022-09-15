<?php

namespace Opcodes\LogViewer\Http\Livewire;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Opcodes\LogViewer\Facades\LogViewer;
use Opcodes\LogViewer\LogFileCollection;
use Opcodes\LogViewer\LogFolder;
use Opcodes\LogViewer\LogFolderCollection;
use Opcodes\LogViewer\LogReader;
use Opcodes\LogViewer\PreferenceStore;

class FileList extends Component
{
    const OLDEST_FIRST = 'asc';

    const NEWEST_FIRST = 'desc';

    const MIN_LOGS_FILE_SIZE_FOR_SCAN_STATE = 50 * 1024 * 1024; // 50 MB

    public ?string $selectedFileIdentifier = null;

    public bool $shouldLoadFilesImmediately = true;

    public string $direction = self::NEWEST_FIRST;

    protected bool $cacheRecentlyCleared;

    protected $listeners = [
        'fullCacheCleared' => 'rescanAllFiles',
        'loadFiles' => 'loadFiles',
    ];

    public function mount(string $selectedFileIdentifier = null)
    {
        $preferenceStore = app(PreferenceStore::class);
        $this->direction = $preferenceStore->get('file_sort_direction', self::NEWEST_FIRST);

        $this->selectedFileIdentifier = $selectedFileIdentifier;

        if (! LogViewer::getFile($this->selectedFileIdentifier)) {
            $this->selectedFileIdentifier = null;
        }
    }

    public function render()
    {
        $folderCollection = LogViewer::getFilesGroupedByFolder()
            // sort the folders and their files
            ->when($this->direction === self::OLDEST_FIRST, function (LogFolderCollection $folders) {
                $folders->sortByEarliestFirst();

                return $folders->each(fn (LogFolder $folder) => $folder->files()->sortByEarliestFirst());
            }, function (LogFolderCollection $folders) {
                $folders->sortByLatestFirst();

                return $folders->each(fn (LogFolder $folder) => $folder->files()->sortByLatestFirst());
            });

        return view('log-viewer::livewire.file-list', [
            'folderCollection' => $this->shouldLoadFilesImmediately && isset($folderCollection) ? $folderCollection : [],
            'cacheRecentlyCleared' => $this->cacheRecentlyCleared ?? false,
            'routeScanCheck' => route('blv.requires-scan'),
            'routeScan' => route('blv.scan-files'),
        ]);
    }

    public function scanFiles(LogFileCollection $fileCollection)
    {
        foreach ($fileCollection as $file) {
            $file->logs()->scan();

            // If there was a scan, it most likely loaded a big index array into memory,
            // so we should clear the instance before checking the next file
            // in order to save some memory.
            LogReader::clearInstance($file);
        }
    }

    public function reloadFiles()
    {
        //
    }

    public function loadFiles()
    {
        $this->shouldLoadFilesImmediately = true;
    }

    public function rescanAllFiles()
    {
        $this->shouldLoadFilesImmediately = false;
        $this->emit('loadFiles');
    }

    public function selectFile(string $name)
    {
        $this->selectedFileIdentifier = $name;
    }

    public function deleteFile(string $fileIdentifier)
    {
        $file = LogViewer::getFile($fileIdentifier);

        if ($file) {
            Gate::authorize('deleteLogFile', $file);
            $file->delete();
        }

        if ($this->selectedFileIdentifier === $fileIdentifier) {
            $this->selectedFileIdentifier = null;
            $this->emit('fileSelected', $this->selectedFileIdentifier);
        }
    }

    public function clearCache(string $fileIdentifier)
    {
        LogViewer::getFile($fileIdentifier)?->clearCache();

        if ($this->selectedFileIdentifier === $fileIdentifier) {
            $this->emit('fileSelected', $this->selectedFileIdentifier);
        }

        $this->cacheRecentlyCleared = true;
    }

    public function updatedDirection($value)
    {
        app(PreferenceStore::class)->put('file_sort_direction', $value);
    }
}
