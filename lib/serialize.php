<?php
/**
 * Serialize Data
 *
 */

include_once( LIB_DIR . 'db.php' );
include_once( LIB_DIR . 'function.php' );


class SerializeData {
	var $dir;
	var $filename = "gran-de.dat";
	var $data;
	
	
	// コンストラクタ
	function SerializeData() {
		$this->dir = ROOT_DIR . "/share/";
		
		if ( file_exists( $this->dir . $this->filename ) ) {
			$fp = file_get_contents( $this->dir . $this->filename );
			$this->data = unserialize( $fp );
		}
	}
	
	
	// データの更新
	function setData( $target , $data , $name = "" ) {
		if ($name != "") {
			$this->data[$target][$name] = $data;
		} else {
			$this->data[$target] = $data;
		}
		
		$this->saveData();
	}
	
	
	// データファイル保存
	function saveData() {
		$fp = fopen( $this->dir . $this->filename , "w" );
		flock( $fp , LOCK_EX );
		fwrite( $fp , serialize( $this->data ) );
		flock( $fp , LOCK_UN );
		fclose( $fp );
	}
	
	
	// データの取得
	function getData( $target , $name = "" ) {
		if ($name != "") {
			return $this->data[$target][$name];
		} else {
			return $this->data[$target];
		}
	}
	
	
	// ランダムにデータを取得
	function getDataRandom( $target ) {
		$limit = $this->data[$target]['limit'];
		$num = count( $this->data[$target] )-2;
		if ( $num < $limit ) $limit = $num;
		
		mt_srand( microtime() * 100000 );
		
		$list = array();
		$cnt = 0;
		while ( $cnt < $limit ) {
			$i = rand( 0 , $num-1 );
			if ( !in_array( $this->data[$target][$i] , $list ) ) {
				array_push( $list , $this->data[$target][$i] );
				$cnt++;
			}
		}
		
		if ( ereg( 'banner' , $target ) ) {
			return $this->getBannerCode( $target , $list );
		} elseif ( $target == 'topics' ) {
			return $this->getTopicsCode( $target , $list );
		}
	}
	
	
	// バナー表示用のコードを生成
	function getBannerCode( $target , $list ) {
		$CSSList = array(
			'banner_pr' => array( 'div' => '' , 'img' => '' ),
			'banner_rec' => array( 'div' => 'btm5' , 'img' => 'border_or' ),
			'banner_pickup' => array( 'div' => 'btm5' , 'img' => 'border_or' )
		);
		
		$str = "";
		foreach ( $list as $data ) {
			$str .= '<div';
			$str .= ( $CSSList[$target]['div'] ? ' class="' . $CSSList[$target]['div'] . '"' : '' ) . '>';
			$str .= '<a href="' . HOME_URL . 'click/cc.php?c=' . $data['click_id'] . '" target="' . $data['target'] . '">';
			$str .= '<img src="' . HOME_URL . 'img/upload/banner/' . $data['banner_img'] . '"';
			$str .= ' alt="' . $data['title'] . '" border="0"';
			$str .= ( $CSSList[$target]['img'] ? ' class="' . $CSSList[$target]['img'] . '"' : '' ) . '></a>';
			$str .= '</div>';
		}
		
		return $str;
	}
	
	
	// トピックスの表示
	function Display_Topics() {
		if( $data = $this->getData( 'topics' ) ) {
			$topics = '<div class="contents">';
			foreach( $data as $list ) {
				$topics .= '<div class="contents"><p class="indent5">';
				$topics .= '<a href="' .HOME_URL. 'click/cc.php?c=' .$list['click_id']. '" ';
				$topics .= 'target="' .$list['target']. '">' .$list['title'] . $list['topics_text']. '</a></p>';
				$topics .= '</div><div class="line_x_or"><img src="../share/img/spacer.gif" alt="spacer" width="1" height="9"></div>';
			}
			$topics .= '</div>';
		}
		return $topics;
	}
}


$sData = new SerializeData();

?>