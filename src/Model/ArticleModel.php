<?php

namespace Model;

class ArticleModel
{

  public function __construct(
    public string $image,
    public string $title,
    public string $chapo,
    public string $content,
    public array  $tags,
 
  ) {}

  public function getImage(){
    return $this->image;
  }
  public function getTitle(){
    return $this->title;
  }
  public function getChapo(){
    return $this->chapo;
  }
  public function getContent(){
    return $this->content;
  }
  public function getTags(){
    return $this->tags;
  }

}
