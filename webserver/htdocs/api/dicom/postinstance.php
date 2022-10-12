<?php
// post instance belonging to study; takes PatientID and PatientName from existing study

/**
* Parse arbitrary multipart/form-data content
* Note: null result or null values for headers or value means error
* @return array|null [{"headers":array|null,"value":string|null}]
* @param string|null $boundary
* @param string|null $content
*/
function parse_multipart_content(?string $content, ?string $boundary): ?array {
  if(empty($content) || empty($boundary)) return null;
  $sections = array_map("trim", explode("--$boundary", $content));
  $parts = [];
  foreach($sections as $section) {
    if($section === "" || $section === "--") continue;
    $fields = explode("\r\n\r\n", $section);
    if(preg_match_all("/([a-z0-9-_]+)\s*:\s*([^\r\n]+)/iu", $fields[0] ?? "", $matches, PREG_SET_ORDER) === 2) {
      $headers = [];
      foreach($matches as $match) $headers[$match[1]] = $match[2];
    } else $headers = null;
    $parts[] = ["headers" => $headers, "value"   => $fields[1] ?? null];
  }
  return empty($parts) ? null : $parts;
}    

  include 'config.php';
  $d=file_get_contents("php://input");
  $t = getallheaders();
  if (array_key_exists("Content-Type", $t)) {
    preg_match_all("/boundary=\"([^;= ]+)\"/", $t["Content-Type"], $r); 
    $boundary= $r[1][0];
	 
    $d = parse_multipart_content($d, $boundary);
    $file = tmpfile();
    fwrite($file, $d[0]["value"]);
    $path = stream_get_meta_data($file)['uri'];
    ob_start();
    passthru($exe . ' "' . str_replace('"', $quote, '--dolua:' .
    'servercommand([[lua:a=Command:GetVR(0x9999,0x0402,true);' .
    'n=tempfile(".dcm");f=io.open(n,"wb");f:write(a);f:close();' .
    'x=DicomObject:new();x:Read(n);print(x.Modality);' .
    'y=DicomObject:new();y.StudyInstanceUID=x.StudyInstanceUID;y.PatientID="";y.PatientName="";' .
    'r=dicomquery("CONQUESTSRV1", "STUDY", y);' .
    'if r[0] then x.PatientID=r[0].PatientID;x.PatientName=r[0].PatientName end;' .
    'addimage(x);os.remove(n);print(x.SOPInstanceUID)]], [[<' . $path . ']])') . '"');
    
    $var = ob_get_contents();
    fclose($file);
    ob_end_clean();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo $var;
  }
