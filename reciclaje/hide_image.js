

function hide_image(ref_id)
{
    var doc = document.querySelector("#" + ref_id);
    if (doc.hidden ) {
        doc.hidden = false;
    }
    else {
        doc.hidden = true;
    }
}