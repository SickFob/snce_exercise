(function() {
  let currentImage = document.getElementById('change-img-btn');
  currentImage.onclick = () => {
    let imgNode = document.getElementById('current-image-block');
    while (imgNode.firstChild) {
      imgNode.removeChild(imgNode.firstChild);
    }
    // create and append label
    let newElImgLabel = document.createElement('label');
    newElImgLabel.htmlFor = 'product_image_path';
    newElImgLabel.innerText = 'Image';
    imgNode.appendChild(newElImgLabel);

    let newElImgInput = document.createElement('input');
    newElImgInput.className = 'form-control-file';
    newElImgInput.id = 'product_image_path';
    newElImgInput.name = 'product[image_path]';
    newElImgInput.type = 'file'
    imgNode.appendChild(newElImgInput);
  };

})();