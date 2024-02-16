<?php

namespace iEducar\Packages\Educacenso\Models;

use App\Models\Individual;
use iEducar\Packages\Educacenso\Enums\EducacensoImportStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class EducacensoInepImport extends Model
{
    protected $fillable = [
        'year',
        'school_name',
        'user_id',
        'status_id',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $studentFileExport): void {
            $studentFileExport->status_id = EducacensoImportStatus::WAITING;
        });
    }

    public function user()
    {
        return $this->belongsTo(Individual::class, 'user_id', 'id');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statusIsError() ? EducacensoImportStatus::ERROR->name() : EducacensoImportStatus::from($this->status_id)->name(),
        );
    }

    public function statusIsError(): bool
    {
        return $this->status_id === EducacensoImportStatus::ERROR->value || ($this->statusIsWaiting() && $this->created_at < now()->subMinutes(5));
    }

    public function statusIsWaiting(): bool
    {
        return $this->status_id === EducacensoImportStatus::WAITING->value;
    }
}
