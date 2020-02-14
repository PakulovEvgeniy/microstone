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

export {getListExcludeSome}