var index_ref = 0;
var index_dis = 0;

function changeImagesFoward_Ref()
{
    var doc = document.querySelector(".referencias");
    var children = doc.childNodes;
 
    console.log("aaaaa");
    console.log(children);
    console.log(doc.childNodes.length);
    index_ref += 1;
    if(index_ref > doc.childNodes.length - 7)
    {
        index_ref = 0;
    }
    for (let i = 3; i < doc.childNodes.length - 3; i++) 
    {
        var x = "ref" + (i - 3);
        if(children[i].getAttribute("id") == ("ref" + (index_ref) )){

            children[i].hidden = false;
        }
        else{
            children[i].hidden = true;
        }
    }
}

function changeImagesBackward_Ref()
{
    var doc = document.querySelector(".referencias");
    var children = doc.childNodes;
    index_ref -= 1;
    if(index_ref < 0)
    {
        index_ref = doc.childNodes.length - 7;
    }
    for (let i = 3; i < doc.childNodes.length - 3; i++) 
    {
        var x = "ref" + (i - 3);
        if(children[i].getAttribute("id") == ("ref" + (index_ref) )){

            children[i].hidden = false;
        }
        else{
            children[i].hidden = true;
        }
    }
}

function hide_References()
{
    var doc = document.querySelector(".referencias");
    if (doc.style.display !== 'none') {
        doc.style.display = 'none';
        var btn = document.querySelector("#hide_referencia");
        btn.innerText  = "+";
    }
    else {
        doc.style.display = 'flex';
        var btn = document.querySelector("#hide_referencia");
        btn.innerText  = "-";
    }
}

function changeImagesFoward_Dis()
{
    var doc = document.querySelector(".disenos");
    var children = doc.childNodes;

    index_dis += 1;
    if(index_dis > doc.childNodes.length - 7)
    {
        index_dis = 0;
    }
    for (let i = 3; i < doc.childNodes.length - 3; i++) 
    {
        var x = "dis" + (i - 3);
        if(children[i].getAttribute("id") == ("dis" + (index_dis) )){

            children[i].hidden = false;
        }
        else{
            children[i].hidden = true;
        }
    }
}

function changeImagesBackward_Dis()
{
    var doc = document.querySelector(".disenos");
    var children = doc.childNodes;
    index_dis -= 1;
    if(index_ref < 0)
    {
        index_dis = doc.childNodes.length - 7;
    }
    for (let i = 3; i < doc.childNodes.length - 3; i++) 
    {
        var x = "dis" + (i - 3);
        if(children[i].getAttribute("id") == ("dis" + (index_dis) )){

            children[i].hidden = false;
        }
        else{
            children[i].hidden = true;
        }
    }
}


function hide_Disenos()
{
    var doc = document.querySelector(".disenos");
    if (doc.style.display !== 'none') {
        doc.style.display = 'none';
        var btn = document.querySelector("#hide_diseno");
        btn.innerText  = "+";
    }
    else {
        doc.style.display = 'flex';
        var btn = document.querySelector("#hide_diseno");
        btn.innerText  = "-";
    }
}