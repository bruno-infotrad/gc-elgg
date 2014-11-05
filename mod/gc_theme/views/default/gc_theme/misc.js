function setHeight($id,$h,$v)
{
        $idtextarea=$id+'-textarea';
        $idsubmit=$id+'-submit';
        document.getElementById($idtextarea).style.height=$h;
        document.getElementById($id).style.height=parseInt($h)+30+'px';
        document.getElementById($idsubmit).style.visibility=$v;
}
function changeHeight($inid,$h,$cw)
{
        $id=$inid;
        $height=$h;
        $canwrite=$cw;
        if ($height == '60px' && $canwrite) {
                setHeight($id,$height,'');
        } else {
                window.setTimeout("setHeight($id,$height,'hidden')",500);
        }
}

function cachee(eid)
{
  var element = document.getElementById(eid);

  element.style.height = '0px';
  element.style.visibility = 'hidden';
}

