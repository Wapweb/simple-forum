<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 26.05.13
 * Time: 21:47
 * To change this template use File | Settings | File Templates.
 */

class Pager {
    // Public methods&objects
    public $count;
    public $on_page;
    public $end;

    public function __construct($count,$on_page,$end,$page = 0) {
        $this->count = $count;
        $this->on_page = $on_page;
        $this->end = $end;
        $this->page = $page;;
    }

    public function get_start() {
        return $this->start = $this->get_page() * $this->on_page - $this->on_page;
    }

    public function print_nav() {
        if ($this->get_total() > 1) {
            return ''.$this->get_nav().'';
        } else return false;
    }

    //Private methods&objects
    private $total;
    private $start;
    private $view;
    private $page;

    private function get_total() {
        return $this->total = intval(($this->count - 1) / ($this->on_page)) + 1;
    }

    public function get_page() {
        if(!$this->page)
            $this->page = isset($_GET['page']) && !empty($_GET['page']) ? intval($_GET['page']) : 1;
        if (empty($this->page) || $this->page < 0) $this->page = 1;
        if ($this->page > $this->get_total()) $this->page = $this->get_total();
        return $this->page;
    }

    private function get_nav() {
        if ($this->get_page() > 1) $this->view = '<li><a href= "'.$this->end. ($this->get_page() - 1) .'">&laquo;</a></li>';
        else
            $this->view = '<li class="disabled"><span>&laquo;</span></li>';
        if (($this->get_page() - 3) > 0) $this->view.= '<li><a href="'.($this->end).'1">1</a></li>'.(($this->get_page() - 4) > 0 ? '<li class="disabled"><span>...</span></li>' : '');
        if($this->get_page() - 2 > 0) $this->view.= '<li><a href= "'.$this->end. ($this->get_page() - 2) .'">'. ($this->get_page() - 2) .'</a></li>';
        if($this->get_page() - 1 > 0) $this->view.= '<li><a href= "'.$this->end. ($this->get_page() - 1) .'">'. ($this->get_page() - 1) .'</a></li>';
        $this->view.= '<li class="active"><a href="">'.$this->get_page().' <span class="sr-only">(current)</span></a></li>';
        if($this->get_page() + 1 <= $this->get_total()) $this->view.= '<li><a href="'.$this->end.($this->get_page() + 1) .'">'. ($this->get_page() + 1) .'</a></li>';
        if($this->get_page() + 2 <= $this->get_total()) $this->view.= '<li><a href= "'.$this->end.($this->get_page() + 2) .'">'. ($this->get_page() + 2) .'</a></li>';
        if (($this->get_page() + 3) <= $this->get_total()) $this->view.= ($this->get_page() + 3 < $this->get_total() ? '<li class="disabled"><span>...</span></li>' : '').'<li><a href="'.($this->end).($this->get_total()).'">'.($this->get_total()).'</a></li>';
        if ($this->get_page() != $this->get_total()) $this->view.= '<li><a href="'.($this->end).($this->get_page() + 1) .'">&raquo;</a></li>';
        else
            $this->view.= '<li class="disabled"><span>&raquo;</span></li>';
        return '<ul class="pagination pagination-sm" style="margin-top: -14px;margin-bottom: 9px;">'.$this->view.'</ul>';
    }
}