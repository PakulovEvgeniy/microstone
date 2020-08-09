function getListExcludeSome(exclude, curGroup) {
  if (curGroup && curGroup.items.length) {
    if (curGroup.items.length>0) {
      return curGroup.items.filter((el, ind) => {
         return ind != exclude;
      });
    } else {
      return [];
    }
  } else {
    return [];
  }
}

function goodsEnd(qty) {
  let c = qty % 100;
  let a1 = [1, 21, 31, 41, 51, 61, 71, 81, 91];
  let a2 = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54, 62, 63, 64, 72, 73, 74, 82, 83, 84, 92, 93, 94];
  if (a1.indexOf(c) != -1) {
    return 'товар';
  } else if(a2.indexOf(c) != -1) {
    return 'товара';
  } else {
    return 'товаров';
  }
}

export {getListExcludeSome, goodsEnd}