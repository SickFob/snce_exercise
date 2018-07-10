(function() {
  var filters = document.getElementsByClassName('tag-filter');
  var products = document.getElementsByClassName('product');
  let tags = [];
  for(var z = 0; z < filters.length; z++) {
    let elem = filters[z];   
    elem.onclick = () => {
      if(elem.checked) {
        tags.push(elem.value);
        for(let i = 0; i < products.length; i++) {
          productTags = products[i].getElementsByTagName('td')[3].getElementsByTagName('span');
          for(let o = 0; o < productTags.length; o++) {
            if(tags.includes(productTags[o].id)) {
              products[i].style.display = 'table-row';
              break;
            } else {
              products[i].style.display = 'none';
            }       
          }
          
        }
      } else {
        let index = tags.indexOf(elem.value);
        if (index > -1) {
          tags.splice(index, 1);
        }
        for(let i = 0; i < products.length; i++) {
          productTags = products[i].getElementsByTagName('td')[3].getElementsByTagName('span');
          for(let o = 0; o < productTags.length; o++) {
            if(tags.length == 0) {
              products[i].style.display = 'table-row';
            } else {
              if(tags.includes(productTags[o].id)) {
                products[i].style.display = 'table-row';
                break;
              } else {
                products[i].style.display = 'none';
              }
            }
          }
        }
      }
    };
  }
})(); // end doc ready


