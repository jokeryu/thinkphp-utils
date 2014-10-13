## Usage
```
$targetDir = C('attach.upload_tmp');
$uploadDir = C('attach.upload_dir');
$data = array();
$uploader = new \Org\RA\Uploader();
$uploader->fileid = $fileid = \Org\Util\String::keyGen();
$uploader->filePath = $targetDir . DIRECTORY_SEPARATOR . $fileid;
$uploader->uploadPath = $uploadDir . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');
$uploader->_after_upload = function ($upData) use ($fileid, $aid) {
    $data['title'] = $upData['fileName'];
    $data['fid'] = $fileid;
    $data['status'] = 1;
    $data['path'] = $upData['path'];
    $data['aid'] = $aid;
    D('Attachment')->doSomething($data);
};
return $uploader->upload();
```

## Todo
- add file size/ext limit support