<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use App\Images;
use DB;
use Cache;

class Crawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'aaa';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function _getUrlContent($url) {
        $handle = fopen($url, "r");
        if ($handle) {
            $content = stream_get_contents($handle, 1024 * 1024);
            return $content;
        } else {
            return false;
        } 
    } 
    /**
     * 从html内容中筛选链接
     */
    public function _filterUrl($web_content) {
        $reg_tag_a = '/查看原图]<\/a><br\s\/><img\ssrc="(.*?)"(\sorg_src="(.*?)")?/ius';
        $result = preg_match_all($reg_tag_a, $web_content, $match_result, PREG_SET_ORDER);
        if ($result) {
            return $match_result;
        } 
    } 
    /**
     * 修正相对路径
     */
    public function _reviseUrl($url_list) {
        $result = array();
        if (is_array($url_list)) {
            foreach ($url_list as $key =>$url_item) {
                $result[$key]['mw_url'] = $url_item[1];
                if(isset($url_item[3])){
                    $result[$key]['normal_url'] = $url_item[3];
                }else{
                    $result[$key]['normal_url'] = $url_item[1];
                }
            }
        } 
        return $result;
    } 
    /**
     * 爬虫
     */
    public function handle() {
        set_time_limit(0);
        $result = array();
        $current_year = Cache::get('year');
        $max_year = date('Y');
        $start_year = isset($current_year)?$current_year:2012;
        for ($i=$start_year; $i <=$max_year ; $i++) { 
            try {
                DB::beginTransaction();
                $url = "http://jandan.net/pic";
                if($i==$max_year){
                    $url = $url;
                }else{
                    $url.='-'.$i;
                }
                $content = $this->_getUrlContent($url);
                $pattern = '/<span.*?class=\".*?current-comment-page.*?\".*?>\[(.*?)\]<\/span>/is';
                $page = preg_match_all($pattern, $content, $matches);
                $max_page = $matches[1][0];
                $current_page = Cache::get('page');
                $start_page = isset($current_page)?$current_page:1;
                for ($k=$start_page; $k <=$max_page; $k++) { 
                    $url_a=$url.'/page-'.$k.'#comments';
                    $content_year = $this->_getUrlContent($url_a);
                    if ($content_year) {
                        $a = $this->_reviseUrl($this->_filterUrl($content_year));
                        if(!empty($a)){
                            foreach ($a as $key => $_a) {
                              //存入数据库
                                $img = new Images;
                                $img->normal_url = $_a['normal_url'];
                                $img->mw_url = $_a['mw_url'];
                                $img->status = 'UNMAT';
                                if(!$img->save()){
                                    throw new Exception("save error", 0);   
                                }
                            }
                        }
                        
                    }
                }
                echo $i.'年抓取成功<br/>';
                DB::commit();
            }catch (Exception $e) {
                DB::rollback();
            }
            Cache::put('year', $i, 60*7);
            Cache::put('page', $max_page+1, 60*7);
        }
    }
}