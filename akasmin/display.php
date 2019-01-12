<?php
include('./docx_reader.php');

if(isset($_GET['doc_tnc']))
{
    $doc = new Docx_reader();
    $doc->setFile('../doc/doc_tnc.doc');
    if(!$doc->get_errors()) {
      $html = $doc->to_html();
      $plain_text = $doc->to_plain_text();
      echo $html;
    } else {
      echo implode(', ',$doc->get_errors());
    }
    echo "\n";
}

if(isset($_GET['doc_faq']))
{
    $doc = new Docx_reader();
    $doc->setFile('../doc/doc_faq.doc');
    if(!$doc->get_errors()) {
      $html = $doc->to_html();
      $plain_text = $doc->to_plain_text();
      echo $html;
    } else {
      echo implode(', ',$doc->get_errors());
    }
    echo "\n";
}
?>
