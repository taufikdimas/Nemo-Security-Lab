<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'stored_name',
        'mime_type',
        'file_size',
        'path',
        'uploaded_by',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isTextFile()
    {
        $textExtensions = ['txt', 'log', 'csv', 'json', 'xml', 'md', 'ini', 'conf', 'yaml', 'yml', 'php', 'env'];
        $extension = pathinfo($this->original_name, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $textExtensions);
    }

    public function extension(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION) ?: 'file');
    }

    /**
     * Human readable size for the repository listings.
     */
    public function humanSize(): string
    {
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? (int) floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);

        return round($bytes / (1024 ** $power), $power === 0 ? 0 : 1) . ' ' . $units[$power];
    }

    /**
     * Icon plus colour for the extension badge shown in the file tables.
     *
     * @return array{icon: string, class: string, label: string}
     */
    public function typeBadge(): array
    {
        return match ($this->extension()) {
            'pdf' => ['icon' => 'bi-file-earmark-pdf', 'class' => 'bg-danger', 'label' => 'PDF'],
            'doc', 'docx' => ['icon' => 'bi-file-earmark-word', 'class' => 'bg-primary', 'label' => 'Dokumen'],
            'xls', 'xlsx' => ['icon' => 'bi-file-earmark-excel', 'class' => 'bg-success', 'label' => 'Spreadsheet'],
            'csv' => ['icon' => 'bi-file-earmark-spreadsheet', 'class' => 'bg-success', 'label' => 'CSV'],
            'txt', 'log', 'md', 'ini', 'conf', 'yaml', 'yml' => ['icon' => 'bi-file-earmark-text', 'class' => 'bg-secondary', 'label' => 'Teks'],
            'json', 'xml' => ['icon' => 'bi-braces', 'class' => 'bg-info', 'label' => 'Data'],
            'png', 'jpg', 'jpeg', 'gif', 'webp' => ['icon' => 'bi-file-earmark-image', 'class' => 'bg-warning text-dark', 'label' => 'Gambar'],
            'zip', 'rar', 'gz', 'tar' => ['icon' => 'bi-file-earmark-zip', 'class' => 'bg-dark', 'label' => 'Arsip'],
            default => ['icon' => 'bi-file-earmark', 'class' => 'bg-light text-dark', 'label' => strtoupper($this->extension())],
        };
    }

    /**
     * True when the browser can render the file inline, which is what the
     * preview panel relies on to decide between an embedded view and a notice.
     */
    public function isPreviewable(): bool
    {
        return $this->isTextFile()
            || in_array($this->extension(), ['png', 'jpg', 'jpeg', 'gif', 'webp', 'pdf'], true);
    }
}