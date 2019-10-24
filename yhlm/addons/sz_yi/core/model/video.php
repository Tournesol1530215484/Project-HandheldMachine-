<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
class Sz_DYi_Video{

        public function index($url) {
            // 获取正常视频地址
            // $url = $_GET['url'];

            if ($url) {
                $parse = parse_url($url);
                isset($parse['host']) && $host = $parse['host'];
                $methods = array(
                    "www.tudou.com" => "tudou",
                    "new-play.tudou.com" => "tudou",
                    "v.youku.com" => "youku",
                    "v.ku6.com" => "ku6",
                    "www.ku6.com" => "ku6",
                    "tvshow.ku6.com" => "ku6",
                    "tv.sohu.com" => "sohu",
                    "video.sina.com.cn" => "sina",
                    "www.56.com" => "five_six",
                    "www.iqiyi.com" => "iqiyi",
                    "v.ifeng.com" => "ifeng",
                    "www.yinyuetai.com" => "yinyuetai",
                );        
                // if (isset($methods[$url])) {
                    return $this->$methods[$host]($url);
                // }
            }
        }

        /**
         * 优酷网
         * // http://www.youku.com
         * @param string $url
         */
        private function youku($url) {
            preg_match('/id_(.*).html/', $url, $url);
            if (isset($url[1])) {
                return "http://player.youku.com/player.php/sid/{$url[1]}/v.swf";
            }
        }
        /**
         * 土豆网
         * // http://www.tudou.com
         * @param string $url
         */
        private function tudou($url) {
            $data = file_get_contents($url);
            // 匹配真实url地址所需的iid编号
            preg_match('/<video.*src="(.*)"/', $data, $result);
            return $data;   
            // if (isset($result[1])) {
                // $url = trim($result[1]);
                // return "http://www.tudou.com/player/skin/plu.swf?iid={$url}";
            // }
        }
        /**
         * 酷6网
         * // http://www.ku6.com
         * @param string $url
         */
        private function ku6($url) {
            // 匹配真实url地址
            // $data = file_get_contents($url);
            // echo $data;
            exit;
            // preg_match('/<video .* src="(.*)"></video>/', $data, $result);
            // <a.*href=.*\>.*\</a\>
            // <video id="my-video_html5_api" class="vjs-tech" style="width:100%;height:100%;" preload="auto" x5-video-player-fullscreen="true" x5-video-player-typ="h5" tabindex="-1" src="http://rbv01.ku6.com/wifi/o_1clgh4ghg4g51mskubkrjqdce15kvs">
            // </video>

            // var_dump($result);      
            // if (isset($result[1])) {
            //     return $result[1];
            // }

            // if (isset($result[1])) {
                // return "http://rbv01.ku6.com/wifi/{$result[1]}";
                // http://rbv01.ku6.com/wifi/o_1c78alktcjca1r3b1vq1791349ckvs
                // http://rbv01.ku6.com/wifi/o_1clgh4ghg4g51mskubkrjqdce15kvs
            // }
        }
        /**
         * 搜狐视频
         * // http://tv.sohu.com
         * @param string $url
         */
        private function sohu($url) {
            $data = file_get_contents($url);
            // 匹配真实url地址
            preg_match('/<meta property="og:video" content="(.*)"/>/', $data, $result);
            if (isset($result[1])) {
                return $result[1];
            }
        }
        /**
         * 新浪播客
         * // http://video.sina.com.cn
         * @param string $url
         */
        private function sina($url) {
            $data = file_get_contents($url);
            // 匹配真实url地址
            preg_match("/swfOutsideUrl:'(.*)',/", $data, $result);
            if (isset($result[1])) {
                return $result[1];
            }
        }
        /**
         * 56网
         * // http://www.56.com
         * @param string $url
         */
        private function five_six($url) {
            // 取出视频所需key
            preg_match('/(v_.*).html/', $url, $result);
            if (isset($result[1])) {
                return "http://player.56.com/{$result[1]}.swf";
            }
        }
        /**
         * 奇艺网
         * // http://www.qiyi.com
         * @param string $url
         */
        private function iqiyi($url) {
            $data = file_get_contents($url);
            // 取出视频所需key
            preg_match('/("videoId":"(.*)")|(data-player-videoid="(.*)")/U', $data, $result);
            if (isset($result[4])) {
                return "http://www.iqiyi.com/player/20130315154043/SharePlayer.swf?vid={$result[4]}";
            }
        }
        /**
         * 凤凰网
         * // http://www.ifeng.com
         * @param string $url
         */ 
        private function ifeng($url) {
            // 取出视频所需key
            preg_match('/d+/(.*)./', $url, $result);
            if (isset($result[1])) {
                return "http://v.ifeng.com/include/exterior.swf?guid={$result[1]}&fromweb=sinaweibo&AutoPlay=true";
            }
        }

  
}
