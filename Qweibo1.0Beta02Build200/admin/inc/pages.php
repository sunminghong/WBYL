<?php
	class MBPage{
		public $all;
		public $pnum = 10;
		public $now;
		public $url;
		public $size = 5;
		public $first = false;
		public $last = false;
		public $prv = false;
		public $next = false;
		public $end = false;
		public $sl = false;
		public $el = false;
		public $loops;
		public $loope;
		public $loopb;
		/*
		 *url 连接地址
		 *all 总记录条数
		 *now 当前页
		 *pnum 每页多少条
		 *size 超过多少页显示.......
		 * */
		function __construct($url,$all,$now=0,$pnum=10){
			if($now <0){
				$now = 0;
			}
			$this->all = ceil($all/$pnum);
			$this->url = htmlencode($url);
			$this->now = $now;
			if($now <= 0){
				$this->prv = false;
			}else if($now >= $this->all-1){
				if($this->all==1){
					$this->next = false;
				}
			}
			if($this->all < 10){
				$this->loops = 0;
				$this->loope = $this->all;
			}else{
				$s = intval($this->size / 2) + 1;
				$e = 7 - $s;
				if($this->now > $s){
					$this->first = true;	
					$this->loops = $this->now - $s + 1; 
				}else{
					$this->loops = 0;
				}
				if($this->now > $s+1){
					$this->sl = true;	
				}
				if($this->all - $this->now > $e){
					$this->last = true;
					$this->loope = $this->now + $e;	
				}else{
					$this->loope = $this->all;
				}
				if($this->all - $this->now > $e + 1){
					$this->el = true;	
				}
			}
		}
		
		public function showpage(){
			echo '<div class="pageinfo">';
			if($this->prv){
				echo '<a href="'.$this->url.'&p='.($this->now-1).'" class="pageP">上一页</a>';
			}
			if($this->first){
				echo '<a href="'.$this->url.'&p=0">1</a>';	
			}
			if($this->sl){
				echo '...';
			}
			if($this->all>1){
				for($i=$this->loops;$i<$this->loope;$i++){
					if($i == $this->now){
						echo '<strong>'.($i+1).'</strong>';	
					}else{
						echo '<a href="'.$this->url.'&p='.$i.'">'.($i+1).'</a>';	
					}
				}
			}
			if($this->el){
				echo '...';
			}
			if($this->last){
				echo '<a href="'.$this->url.'&p='.($this->all-1).'">'.$this->all.'</a>';	
			}
			if($this->next){
				echo '<a href="'.$this->url.'&p='.($this->now+1).'" class="pageN">下一页</a>';
			}
			echo '</div>';
		}
	}
?>
