#DT Uploader Bot

###1. Introduction

This is a command line script - Bot. It resizes given images and saves them to remote cloud storage.

#####The workflow divided into the following independent steps:

2. Schedule list of images to be processed.
2. Resize scheduled images.
2. Upload resized image to cloud storage.

Each step works with its own queue, e.g. scheduler adds filename to resize queue, resizer takes filename from resizequeue, does resize and put filename to upload queue and so on.

#####After all there are the following queues:

3. resize - files ready to be resized.
3. upload - files ready to be uploaded.
3. done - completed files.
3. failed - failed files.

If any step fails it move file to failed queue. For example, if image could not be uploaded right now (e.g. due to network problems) corresponding filename was moved to failed queue and it should be possible to retry later.

###2. CLI Script

Bot implemented as a PHP command line script named bot.php. Running bot without arguments show full list of supported commands:

```
$ bot.php
Uploader Bot

Usage:
  command [arguments]

Available commands:
  schedule  Add filenames to resize queue
  resize    Resize next images from the queue
  status    Output current status in format %queue%:%number_of_images%
  upload    Upload next images to remote storage
  retry     Moves all URLs from failed queue back to resize queue
  status    Outputs all queues with a count of URLs in each of them
```

  Description of commands are listed below in this section.

###3. Scheduler

Accepts a path to the directory with images and schedule them for resize, i.e. adds to resize queue.

```
$ bot schedule ./images
```
Directory images contains only images in different formats:

```
$ ls images

first.png second.jpg third.png 5.jpg
```

###4. Resizer

Takes next count of images from resize queue and resizes them to 640x640 pixels in jpg format. If image is not a square shape resizer should make it square by means of adding a white background. If there is an error URL should be moved to failedqueue.

```
$ bot resize [-n <count>]
```

If parameter -n is omitted resize should work on all images from resize queue.
Resized images should be stored in directory called images_resized. If resize goes well original image should be removed fromimages directory.

###5. Uploader

Uploads next count of images from upload queue to one of the remote storages. Type of cloud storage and corresponding credentials should be set in config file. There can be only one remote storage at the moment. Bot should support one storage from the list:

4. Dropbox
4. Google Drive
4. Amazon S3

After image is uploaded move its filename to done queue. In case of any error move filename to failed queue.

```
$ bot upload [-n <count>]
```

If parameter -n is omitted upload should work on all images from the queue.

###6. Monitoring

Outputs all queues with a count of URLs in each of them.

```
$ bot status
Images Processor Bot
Queue Count
resize 0
upload 12
done 42
failed 4
```

###1. Rescheduler

Moves all URLs from failed queue back to resize queue.

```
$ bot.php retry [-n <count>]
```
