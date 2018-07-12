(function() {
  let products = document.getElementsByClassName('product');
  let tagToSearch = [];
  let searchFilter = document.getElementById('search-filter');

  // Filter when typed a Letter
  searchFilter.addEventListener('keypress', e => {
    tagToSearch.push(e.key);
    filterTag(tagToSearch, products);
  });

  // Check if backspace
  searchFilter.addEventListener('keydown', e => {
    if(e.key == 'Backspace') {
      tagToSearch.pop();
    }
    filterTag(tagToSearch, products);
  });
})(); // end doc ready


/**
 * 
 * Description Filter (mysql LIKE) on product list
 * 
 * @param {string} array 
 * @param {element} products 
 * 
 */
function filterTag(array, products) {
  searchedTag = array.join("");
  for(let i = 0; i < products.length; i++) {
    productTags = products[i].getElementsByTagName('td')[2].getElementsByTagName('span');
    for(let o = 0; o < productTags.length; o++) {
      if(searchedTag.length != 0) {
        if(productTags[o].innerText.indexOf(searchedTag) > -1) {
          products[i].style.display = 'table-row';
          break;
        } else {
          products[i].style.display = 'none';
        }
      } else {
        products[i].style.display = 'table-row';
      }
    }
  }
}


