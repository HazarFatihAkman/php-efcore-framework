<?php

namespace View\Components;

class PageComponents {
    public function TestLink($baseUrl,$Model){
        $strHtml = "'".$Model->Title."'".$baseUrl;
        return $strHtml;
    }
}