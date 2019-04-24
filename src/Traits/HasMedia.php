<?php

namespace Spotmarket\MediaLibrary\Traits;

use Illuminate\Support\Facades\Storage;
use Spotmarket\MediaLibrary\Models\Media;

trait HasMedia
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (is_null($this->mediaPackageSettings)) {
            throw new \Exception('Media Package: Missing media package settings definitions');
        }

        if (!isset($this->mediaPackageSettings['multipleFiles'])) {
            throw new \Exception('Media Package: Missing multipleFiles definition');
        }

        if (!isset($this->mediaPackageSettings['folder'])) {
            throw new \Exception('Media Package: Missing folder definition');
        }
    }

    public function media()
    {
        return $this->morphMany('Spotmarket\MediaLibrary\Models\Media', 'model');
    }

    public function getMediaAttribute()
    {
        if (isset($this->mediaPackageSettings['groups'])) {
            $array = [];
            foreach($this->mediaPackageSettings['groups'] as $group) {
                $array[$group] = $this->media()
                    ->where('group', $group);
                if($this->mediaPackageSettings['multipleFiles']) {
                    $array[$group] = $array[$group]->get()->pluck('link');
                } else {
                    $array[$group] = $array[$group]->pluck('link')->first();
                }
            }
            return $array;
        }

        if ($this->mediaPackageSettings['multipleFiles']) {
            return $this->media()->get()->pluck('link');
        } else {
            return $this->media()->pluck('link')->first();
        }
    }

    public function addMedia($file, $group = null)
    {
        if (is_null($group)) {
            $position = $this->media()->count() + 1;
            $group = 'default';
        } else {
            $position = $this->media()->where('group', $group)->count() + 1;
        }

        if (is_null($file)) {
            return false;
        }

        $path = $file->store("public/$this->folder/$group");
        $media = new Media([
            'link' => Storage::url($path),
            'size' => Storage::size($path),
            'position' => $this->media()->count() + 1,
            'group' => !is_null($group) ? $group : 'default'
        ]);
        $this->media()->save($media);
    }

    public function deleteMedia($link = null)
    {
        if (!is_null($link)) {
            $this->media()->where('link', $link)->delete();
            return true;
        }

        foreach($this->media() as $media) {
            $media->delete();
        }
        return true;
    }
}
