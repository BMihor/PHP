<?php

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class UploadListener
{
    public function onUpload(PostPersistEvent $event)
    {
        $file = $event->getFile();
        $path = '/uploads/'.$event->getType().'/'.$file->getFilename();

        $response = $event->getResponse();
        $response['key'] = 'value';
        $response['foo'] = 'bar';

        $response['name'] = $file->getFilename();
        $response['path_raw'] = $path;
    }
}