#!/usr/bin/env php
<?php
require_once('./config.php');

function usage()
{
    echo "
Uploader Bot

Usage:
  command [arguments]

Available commands:
  schedule  Add filenames to resize queue
  resize    Resize next images from the queue
  status    Output current status in format %queue%:%number_of_images%
  upload    Upload next images to remote storage
:
";
    exit(1);
};

// usage
if($argc < 2)
{
    usage();
};

echo "[".$argv[1]."]\n";

$rabbit = new AMQPConnection(
        array(
            'host' => $conf['host'],
            'port' => $conf['port'],
            'login' => $conf['login'],
            'password' => $conf['password']
        )
);
$rabbit->connect();
$channel = new AMQPChannel($rabbit);
$queue = new AMQPExchange($channel);
$queue->setName('amq.direct');

// commands
switch ($argv[1])
{
    case 'schedule':
        echo "schedule:\n";
        $ImageList = ReadImagesDirectory($conf['srcdir']);
        print_r($ImageList);
        
        // adding queue for images
        $q = new AMQPQueue($channel);
        $q->setName('images_to_resize');
        $q->declare();
        $q->bind('amq.direct', 'schedule');
        
        // publish image names
        foreach ($ImageList as &$FileName)
        {
            $queue->publish($FileName, 'schedule');
        };
        
        break;

    case 'resize':
        echo "resize:\n";
        
        $IR = new ResizeImage();
        // adding queue for reading images
        $q = new AMQPQueue($channel);
        $q->setName('images_to_resize');
        $q->declare();
        $q->bind('amq.direct', 'schedule');
        
        // reading list
        while ($image = $q->get())
        {
            print_r($image);
            echo "\n";
            
            // resize image
            $IR->load($image->getBody());
            
            $q->ack($image->getDeliveryTag());
        };

        
        break;

    case 'status':
        echo "status:\n";
        break;

    case 'upload':
        echo "upload:\n";
        break;

    case 'retry':
        echo "retrying:\n";
        break;

    case 'status':
        echo "status:\n";
        break;

    default:
        usage();
        break;
};

$rabbit->disconnect();


// reading images directory list
function ReadImagesDirectory($dir)
{
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh))) {
        $files[] = $filename;
    }
    return preg_grep('/\.(jpg|jpeg|png|gif)$/i', $files);
};

class ResizeImage {

    public $quality = 80;
    protected $image, $filename, $original_info, $width, $height;
    
    function __construct($filename = null, $width = null, $height = null, $color = null)
    {
        if ($filename)
        {
            $this->load($filename);
        }
    }
    
    function load($filename) {
        // Require GD library
        if (!extension_loaded('gd')) {
            throw new Exception('Required extension GD is not loaded.');
        }
        $this->filename = $filename;
        print_r($this->filename);
        return $this->get_meta_data();
    }
    
}

?>