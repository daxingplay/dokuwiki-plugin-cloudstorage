<?php
/**
 * DokuWiki Plugin oss (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  daxingplay <daxingplay@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_cloudstorage extends DokuWiki_Action_Plugin {

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {

       $controller->register_hook('FETCH_MEDIA_STATUS', 'BEFORE', $this, 'handle_fetch_media_status');
//       $controller->register_hook('MEDIA_UPLOAD_FINISH', 'BEFORE', $this, 'handle_media_upload_finish');
   
    }

    /**
     * [Custom event handler which performs action]
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */

    public function handle_fetch_media_status(Doku_Event &$event, $param) {
        global $conf;
        $data = $event->data;
        $status = $data['status'];
        if ($status == 200) {
            $file = $data['file'];
            $file_relative_path = utf8_decodeFN(str_replace($conf['mediadir'], '', $file));

            if ($this->getConf('cdn_url')) {
                $event->data['status'] = 301;
                if ($this->getConf('force_decode_file_name')) {
                    $file_relative_path = urlencode($file_relative_path);
                }
                $event->data['statusmessage'] = $this->getConf('cdn_url') . $file_relative_path;
            }
        }
    }

    public function handle_media_upload_finish(Doku_Event &$event, $param) {
//        var_dump($event->data);
    }

}

// vim:ts=4:sw=4:et:
