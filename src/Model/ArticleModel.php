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

  ) {
  }

  public function getImage(): string
  {
    return $this->image;
  }
  public function getTitle(): string
  {
    return $this->title;
  }
  public function getChapo(): string
  {
    return $this->chapo;
  }
  public function getContent(): string
  {
    return $this->content;
  }
  public function getTags(): array
  {
    return $this->tags;
  }
}
