let upload_btn = document.getElementById("file-upload");
var rslt = document.getElementById("update_result");
let input_file;
let deley_render;

function DropFile(dropAreaId, fileListId) {
  // ("drop-file", "files")
  let dropArea = document.getElementById(dropAreaId);
  let fileList = document.getElementById(fileListId);
  // let excel_file = document.getElementById("file-upload");

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  function highlight(e) {
    preventDefaults(e);
    dropArea.classList.add("highlight");
  }

  function unhighlight(e) {
    preventDefaults(e);
    dropArea.classList.remove("highlight");
  }

  function handleDrop(e) {
    input_file = e.target.cloneNode();
    console.log(e.target);
    unhighlight(e);
    let dt = e.dataTransfer;
    let files = dt.files;

    handleFiles(files);
    const fileList = document.getElementById(fileListId);
    if (fileList) {
      fileList.scrollTo({ top: fileList.scrollHeight });
    }
  }

  function handleFiles(files) {
    console.log(files);
    files = [...files];
    files.forEach(previewFile);
  }

  function previewFile(file) {
    // 파일 업로드 확장자 체크
    if (file.name != "") {
      var ext = file.name.split(".").pop().toLowerCase();
      console.log(file.name);
      if (ext === "xml") {
        // xml 파일
        readXml(file);
      } else if (ext == "xls" || ext == "xlsx") {
        // 엑셀 파일
        var ext_list = ["xls", "xlsx"];
        rslt.style.display = "none";
        if (!ext_list.includes(ext)) {
          alert("엑셀파일만 등록가능합니다.");
          return;
        } else {
          while (fileList.hasChildNodes()) {
            fileList.removeChild(fileList.firstChild);
          }
          var result = confirm("사원리스트를 업데이트 하시겠습니까?");
          if (result) {
            readExcel(file);
            deley_render = setTimeout(() => {
              fileList.appendChild(renderFile(file));
            }, 500);
          }
        }
      } else {
        alert("엑셀파일이나 xml파일만 등록 가능합니다.");
      }
    }
  }

  function renderFile(file) {
    let fileDOM = document.createElement("div");
    fileDOM.className = "file";
    fileDOM.innerHTML = `
      <div class="thumbnail">
        <img src="./excel_icon.png" alt="파일타입 이미지" class="image">
      </div>
      <div class="details">
        <header class="header">
          <span class="name">${file.name}</span>
          <span class="size">${file.size}</span>
        </header>
        <div class="progress">
          <div class="bar"></div>
        </div>
        <div class="status">
          <span class="percent">100% done</span>
          <span class="speed">900KB/sec</span>
        </div>
      </div>
    `;
    return fileDOM;
  }

  dropArea.addEventListener("dragenter", highlight, false);
  dropArea.addEventListener("dragover", highlight, false);
  dropArea.addEventListener("dragleave", unhighlight, false);
  dropArea.addEventListener("drop", handleDrop, false);
  dropArea.addEventListener("drop", handleDrop, false);
  return {
    handleFiles,
  };
}

const dropFile = new DropFile("drop-file", "files");

function readExcel(file) {
  let reader = new FileReader();
  //console.log(file);
  reader.onload = function () {
    let data = reader.result;
    let workBook = XLSX.read(data, { type: "binary" });
    workBook.SheetNames.forEach(function (sheetName) {
      // console.log('SheetName: ' + sheetName);
      let rows = XLSX.utils.sheet_to_json(workBook.Sheets[sheetName]);
      // console.log('eno: ' + rows[0]['eno']+' team: ' + rows['team']+' name: ' + rows['name']+' position: ' + rows['position']);
      console.log(JSON.stringify(rows));

      insert_data(rows);
    });
  };
  reader.readAsBinaryString(file);
}

function readXml(file) {
  let reader = new FileReader();

  reader.onload = function (e) {
    var xmlString = e.target.result;

    // 문자열을 XML로 파싱합니다.
    var parser = new DOMParser();
    var xmlDoc = parser.parseFromString(xmlString, "text/xml");

    // 파싱된 XML을 xmlData에 할당합니다.
    var xmlData = xmlDoc.documentElement;

    // xmlData를 사용하여 원하는 작업을 수행합니다.
    var obj = xmlToJson(xmlData);
    insert_xml_data(obj);
  };

  reader.readAsText(file);
}

function xmlToJson(xml) {
  // Create the return object
  var obj = {};

  if (xml.nodeType == 1) {
    // element
    // do attributes
    if (xml.attributes.length > 0) {
      obj["@attributes"] = {};
      for (var j = 0; j < xml.attributes.length; j++) {
        var attribute = xml.attributes.item(j);
        obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
      }
    }
  } else if (xml.nodeType == 3) {
    // text
    obj = xml.nodeValue;
  }

  // do children
  // If all text nodes inside, get concatenated text from them.
  var textNodes = [].slice.call(xml.childNodes).filter(function (node) {
    return node.nodeType === 3;
  });
  if (xml.hasChildNodes() && xml.childNodes.length === textNodes.length) {
    obj = [].slice.call(xml.childNodes).reduce(function (text, node) {
      return text + node.nodeValue;
    }, "");
  } else if (xml.hasChildNodes()) {
    for (var i = 0; i < xml.childNodes.length; i++) {
      var item = xml.childNodes.item(i);
      var nodeName = item.nodeName;
      if (typeof obj[nodeName] == "undefined") {
        obj[nodeName] = xmlToJson(item);
      } else {
        if (typeof obj[nodeName].push == "undefined") {
          var old = obj[nodeName];
          obj[nodeName] = [];
          obj[nodeName].push(old);
        }
        obj[nodeName].push(xmlToJson(item));
      }
    }
  }
  return obj;
}

function insert_xml_data(xmlData) {
  var filename = xmlData.filename.split(".")[0] + ".xml";
  var width = xmlData.size.width;
  var height = xmlData.size.height;
  var channel = xmlData.size.depth;
  var object1 = "";
  var object2 = "";
  var object3 = "";
  var object4 = "";
  var object5 = "";
  var object6 = "";

  xmlData.object.forEach(function (data) {
    switch (data.name) {
      case "helmet":
        object1 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
      case "goggles":
        object2 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
      case "mask":
        object3 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
      case "vest":
        object4 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
      case "belt":
        object5 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
      case "vestplusbelt":
        object6 =
          "(" +
          data.bndbox.xmin +
          "," +
          data.bndbox.ymin +
          "," +
          data.bndbox.xmax +
          "," +
          data.bndbox.ymax +
          ")";
        break;
    }
  });

  let dataObject = {
    filename: filename,
    width: width,
    height: height,
    channel: channel,
    object1: object1,
    object2: object2,
    object3: object3,
    object4: object4,
    object5: object5,
    object6: object6,
  };

  $.ajax({
    type: "POST",
    data: dataObject,
    url: "./excelDownload.php",
    success: function () {},
    error: function () {
      alert("텅신실패");
    },
  });
}

function insert_data(rows) {
  console.log(rows);
  if (
    rows[0].hasOwnProperty("id") &&
    rows[0].hasOwnProperty("title") &&
    rows[0].hasOwnProperty("description") &&
    rows[0].hasOwnProperty("created") &&
    rows[0].hasOwnProperty("author")
  ) {
    var all_data = {
      list: rows,
    };
    console.log(all_data);
    $.ajax({
      type: "POST",
      data: all_data,
      dataType: "JSON",
      url: "./insert.php",
      success: function (data) {
        console.log(data);
        var tot = document.getElementById("total");
        var upd = document.getElementById("update");
        var ins = document.getElementById("insert");
        setTimeout(() => {
          rslt.style.display = "block";
          tot.innerText = data.total + "건";
          upd.innerText = data.update + "건";
          ins.innerText = data.insert + "건";
          failed.innerText = data.failed + "건";
        }, 500);
      },
      error: function () {
        alert("텅신실패");
      },
    });
  } else {
    clearTimeout(deley_render);
    alert("양식에 맞는 엑셀 데이터를 넣어주세요!");
  }
}
