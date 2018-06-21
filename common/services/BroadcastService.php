<?php
namespace app\common\services;

class BroadcastService{

    public static function pageBanner($url,$page,$total,$pagesize = 10,$showPage = 5,$act){

        $total_pages   = ceil($total/$pagesize);//总页数
        $pageBanner = '';
        if($page>1){
            /*$pageBanner.= "<a class='c-btn s-gift-page' href='javascript:".$method."(".($page-1).")'>首页</a>";*/
//            $pageBanner.= "<a class='c-btn s-gift-page s-gift-prepage' href='javascript:".$method."(".($page-1).")'>.</a>";
            $pageBanner .= '<a class="c-btn s-gift-page s-gift-prepage" href="'.$url.'?page='.($page-1).'">.</a>';
        }
        else{
//            $pageBanner.= "<a disabled class='c-btn s-gift-page s-gift-prepage' href='javascript:".$method."(".($page-1).")'>.</a>";
            $pageBanner .= '<a disabled class="c-btn s-gift-page s-gift-prepage" href="#">.</a>';
        }
        //计算偏移量
        $pageoffset=($showPage-1)/2;
        //初始化数据
        $start=1;
        $end=$total_pages;

        if($total_pages>$showPage){
            if($page>$pageoffset+1){
//                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$start.")'>".$start."</a>";
                $pageBanner .= '<a class="c-btn s-gift-page" href="'.$url.'?page='.$start.'">'.$start.'</a>';
                //$pageBanner.="<a href='javascript:".$method."(".$start.")'>".$start."</span>";
                $pageBanner.="<a>...</a>";
            }
            if($page>$pageoffset){
                $start=$page-$pageoffset;
                $end=$total_pages>$page+$pageoffset?$page+$pageoffset:$total_pages;
            }
            else {
                $start=1;
                $end=$total_pages>$showPage?$showPage:$total_pages;
            }
            if($page+$pageoffset>$total_pages){
                $start=$start-($page+$pageoffset-$end);
            }
        }
        for($i=$start;$i<=$end-1;$i++){
            if($page==$i){
//                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$i.")'  class='".$act."'><span>".$i."</span></a>";
                $pageBanner .= '<a class="c-btn s-gift-page '.$act.'" href="'.$url.'?page='.$i.'">'.$i.'</a>';
            }
            else{
//                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$i.")'>".$i."</a>";
                $pageBanner .= '<a class="c-btn s-gift-page" href="'.$url.'?page='.$i.'">'.$i.'</a>';
            }
        }
        //尾部省略
        if($total_pages>$showPage&& $total_pages>$page+$pageoffset){
            $pageBanner.="<a>...</a>";
        }
        if($page<$total_pages){
            $pageBanner .= '<a class="c-btn s-gift-page" href="'.$url.'?page='.$total_pages.'">'.$total_pages.'</a>';
            $pageBanner .= '<a class="c-btn s-gift-page s-gift-nextpage" href="'.$url.'?page='.($page+1).'">.</a>';
        }
        if($page==$total_pages){
//            $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$total_pages.")' class='".$act."'><span>".$total_pages."</span></a>";
            $pageBanner .= '<a class="c-btn s-gift-page" href="'.$url.'?page='.$total_pages.'">'.$total_pages.'</a>';
        }
        return $pageBanner;
    }
}