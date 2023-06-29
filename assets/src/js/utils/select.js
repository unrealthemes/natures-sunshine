export default function customSelect() {
  const selects = document.querySelectorAll('.select select');

  const setSelectedOptions = options => {
    [...options].forEach(option => {
      if (!option.selected) {
        option.removeAttribute('selected');
      } else {
        option.setAttribute('selected', true);
      }
    });
  }

  const changeEvent = (e) => {
    const selectOptions = e.target.options;
    setSelectedOptions(selectOptions);
  }

  const createDiv = (...classes) => {
    const node = document.createElement("div");
    node.classList.add(...classes);
    return node;
  }

  const closeAllSelect = (select) => {
    const selectItems = document.getElementsByClassName('select-items');
    const selected = document.getElementsByClassName('select-selected');
    const arr = [];

    for (let i = 0; i < selected.length; i++) {
      if (select === selected[i]) {
        arr.push(i);
      } else {
        selected[i].classList.remove('active');
      }
    }

    for (let i = 0; i < selectItems.length; i++) {
      if (arr.indexOf(i) && !selectItems[i].classList.contains('select-hide')) {
        selectItems[i].classList.add('select-hide');
        selected[i].previousElementSibling.dispatchEvent(new Event('change'));
      }
    }
  }

  selects && selects.forEach(select => {
    const options = select.options;
    const selectSelected = createDiv('select-selected');
    selectSelected.innerText = select.dataset.start || (options[select.selectedIndex] && options[select.selectedIndex].text);
    options[select.selectedIndex] && options[select.selectedIndex].disabled && selectSelected.classList.add('placeholder');
    select.parentElement.appendChild(selectSelected);

    const createOptions = () => {
      const selectContainer = createDiv('select-items', 'select-hide');
      Array.from(options).forEach((option, index) => {
        const isSelected = option.selected;
        const isDisabled = option.disabled;
        const item = createDiv('select-item');
        item.setAttribute('data-item', index.toString());
        item.innerText = option.text;
        if (isSelected && !option.hidden) {
          item.className += ' selected';
        }
        if (isDisabled && !option.hidden) {
          item.className += ' disabled';
        }
        !option.hidden && selectContainer.appendChild(item);

        item.addEventListener('click', function (e) {
          const currentSelect = this.parentNode.parentNode.getElementsByTagName('select')[0];
          const currentSelected = this.parentElement.previousElementSibling;
          const currentItems = this.parentElement.getElementsByClassName('select-item');
          for (let i = 0; i < currentItems.length; i++) {
            currentItems[i].classList.remove('selected');
          }
          if (options[index].innerText.trim() === this.innerHTML.trim()) {
            currentSelect.selectedIndex = index;
            currentSelect.dispatchEvent(new Event('change'));
            currentSelected.innerHTML = this.innerHTML;
            currentSelected.classList.contains('placeholder') && currentSelected.classList.remove('placeholder')
          }
          this.classList.add('selected');
        });
      });
      return selectContainer;
    }

    select.parentElement.appendChild(createOptions());

    select.addEventListener('change', changeEvent);

    selectSelected.addEventListener('click', function (e) {
      e.stopPropagation();
      closeAllSelect(this);
      this.nextElementSibling.classList.toggle('select-hide');
      this.classList.toggle('active');

      !this.classList.contains('active') && this.previousElementSibling.dispatchEvent(new Event('change'));
    });
  });

  selects && document.addEventListener('click', closeAllSelect);
}