function getFiles() {
    var files = $(".file");
    var filesData = new Array(files.length);
    var i = 0;
    $.each(files, function(key,value) {
        filesData[i]= value.id;
        console.log(value.id);
        i++;
    });
    return filesData;
}
