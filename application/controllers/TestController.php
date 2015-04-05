<?php
class TestController extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->file('application/classes/VideoHandler.php');
    }

    function testVid() {

        $vh = new VideoHandler('desi-swag.mp4');
        $thumbs = $vh->generateThumbnail();

        $vh->waterMarkImage("varun1505");
        $vh->process("varun1505");

    }
}