<?php

class DirTree {

  private $root = null;
  var $dir_tree = array();

  function __construct($root=null) {
    $this->root = $root;
  }

  function set_root($root) {
    $this->root = $root;
  }

  function get() {
    $this->dir_tree = array();
    fn_directory_recursion($this->root, '!.*/,*.*', array(&$this, 'push'));
    return $this->dir_tree;
  }

  function push($path, $args) {
    $this->dir_tree[] = $path;
  }
}

/* 
 * phpでディレクトリツリーを辿りファイルを再帰的に処理する関数 | とりさんのソフト屋さん
 * http://soft.fpso.jp/develop/php/entry_2818.html
 */

/**
 * $targetを再帰的に$patternに一致したファイル走査し、$callbackを実行する関数
 *
 * @param string $target 再帰して検索させるディレクトリ
 * @param string $pattern 調べるパターン。シェルワイルドカードで指定。ディレクトリは除外パターン(先頭に!)のみ有効。カンマ区切りで複数指定可
 * @param string $callback 検索されたファイルを処理する関数名
 * @param array $args $callbackに渡す引数(可変ではない)
 * @return void
 */
function fn_directory_recursion($target, $pattern, $callback='', $args=null)
{
  if (!is_dir($target)) return false;

  $target = add_last_slash($target); //ディレクトリは最後に/を付加

  $nodes = array();
  //ディレクトリ内のファイル名を１つずつを取得
  $node = scandir($target);
  $node_count = count($node);
  for ($i = 0; $i < $node_count; $i++) {
    if ($node[$i] !== '.' && $node[$i] !== '..') {
      $nodes[] = $node[$i]; //ファイルリスト作成
    } 
  }

  //ファイルリストをチェック
  foreach ($nodes as $node) {
    $path = $target.$node;
    if (is_dir($path)) {
      //ディレクトリの場合、除外パターンだけ有効
      $node = add_last_slash($node); //ディレクトリは最後に/を付加
      if (is_pattern_match($pattern, $node) !== -1) { //除外以外の場合
        fn_directory_recursion($path, $pattern, $callback, $args);
      }
    } else if(is_file($path)) {
      //ディレクトリの場合、一致パターンだけ有効
      if (is_pattern_match($pattern, $node)) {
        if (is_array($callback)) {
          //正しいものとしてチェックを行わない。
          $instace = $callback[0];
          $method = $callback[1];
          $instace->$method($path,$args);
        } else {
          $callback($path,$args); //あるものとして存在チェックは行わない。
        }
      }
    }
  }
}

/**
 * $strの最後に/を付加する関数。$strの最後に/が初めから付いている場合は付加しない。
 *
 * @param string $str /を付加する文字列
 * @return string /を最後に付加した文字列
 */
function add_last_slash($str)
{
  if (!preg_match('/\/$/',$str)) {
    $str .= '/';
  }
  return $str;
}

/**
 * $strが$patternに一致していることを調べる関数
 *
 * @param string $pattern 調べるパターン。シェルワイルドカードで指定。ディレクトリは除外パターン(先頭に!)のみ有効。カンマ区切りで複数指定可
 * @param string $str 調べる文字列
 * @return int -1:除外パターン一致、1:パターン一致、0:一致パターン無し
 */
function is_pattern_match($pattern, $str)
{
  $pats = explode(',',$pattern);
  foreach ($pats as $pat) {
    //頭が!で始まるパターンは除外パターン
    if (preg_match('/^!(.*)/',$pat,$m)) {
      $pat = $m[1];
      if (fnmatch($pat, $str)) {
        return -1; //除外
      }
    } else {
      if (fnmatch($pat, $str)) {
        return 1; //一致
      }
    }
  }
  return 0;
}

?>
