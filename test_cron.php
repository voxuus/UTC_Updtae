<?php
$path = "cache";
if(!rmdir($path))
  {
  echo ("Could not remove $path");
  }
  @mkdir("cache", 0777, 1);
  ?> 