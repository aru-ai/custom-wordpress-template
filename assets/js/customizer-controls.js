(function () {
  const parseItems = function (value) {
    if (!value) return [];

    try {
      const parsed = JSON.parse(value);
      return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
      return [];
    }
  };

  const createField = function (label, key, type, value, options) {
    const wrapper = document.createElement('label');
    wrapper.className = 'mbt-repeater-item__field';

    const title = document.createElement('span');
    title.className = 'mbt-repeater-item__label';
    title.textContent = label;
    wrapper.appendChild(title);

    let input;

    if (type === 'textarea') {
      input = document.createElement('textarea');
      input.rows = 4;
      input.value = value || '';
    } else if (type === 'select') {
      input = document.createElement('select');
      (options || []).forEach(function (option) {
        const optionEl = document.createElement('option');
        optionEl.value = option.value;
        optionEl.textContent = option.label;
        optionEl.selected = String(option.value) === String(value || '');
        input.appendChild(optionEl);
      });
    } else {
      input = document.createElement('input');
      input.type = type || 'text';
      input.value = value || '';
    }

    input.dataset.field = key;
    wrapper.appendChild(input);

    return wrapper;
  };

  const renderControl = function (control) {
    const hiddenInput = control.querySelector('.mbt-repeater-control__value');
    const list = control.querySelector('.mbt-repeater-control__list');
    const addButton = control.querySelector('.mbt-repeater-control__add');
    const defaultSource = control.getAttribute('data-default-source') || 'Based on Google Review';

    if (!hiddenInput || !list || !addButton) return;

    let items = parseItems(hiddenInput.value || list.dataset.items || '[]');

    const sync = function () {
      hiddenInput.value = JSON.stringify(items);
      hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
      hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const buildItem = function (item, index) {
      const card = document.createElement('div');
      card.className = 'mbt-repeater-item';

      const header = document.createElement('div');
      header.className = 'mbt-repeater-item__header';

      const title = document.createElement('strong');
      title.textContent = item.name && item.name.trim() !== '' ? item.name : 'New Review';
      header.appendChild(title);

      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'button-link-delete mbt-repeater-item__remove';
      removeButton.textContent = 'Remove';
      removeButton.addEventListener('click', function () {
        items.splice(index, 1);
        render();
      });
      header.appendChild(removeButton);

      card.appendChild(header);
      card.appendChild(createField('Reviewer Name', 'name', 'text', item.name || ''));
      card.appendChild(createField('Source Label', 'source', 'text', item.source || defaultSource));
      card.appendChild(createField('Review Text', 'content', 'textarea', item.content || ''));
      card.appendChild(createField('Stars', 'rating', 'select', item.rating || 5, [
        { value: 5, label: '5 Stars' },
        { value: 4, label: '4 Stars' },
        { value: 3, label: '3 Stars' },
        { value: 2, label: '2 Stars' },
        { value: 1, label: '1 Star' }
      ]));

      card.querySelectorAll('[data-field]').forEach(function (field) {
        field.addEventListener('input', function () {
          items[index][field.dataset.field] = field.value;

          if (field.dataset.field === 'name') {
            title.textContent = field.value.trim() !== '' ? field.value : 'New Review';
          }

          sync();
        });

        field.addEventListener('change', function () {
          items[index][field.dataset.field] = field.value;
          sync();
        });
      });

      return card;
    };

    const render = function () {
      list.innerHTML = '';

      items.forEach(function (item, index) {
        list.appendChild(buildItem(item, index));
      });

      sync();
    };

    addButton.addEventListener('click', function () {
      items.push({
        name: '',
        source: defaultSource,
        content: '',
        rating: 5
      });
      render();
    });

    render();
  };

  const initControls = function () {
    document.querySelectorAll('.mbt-repeater-control').forEach(renderControl);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initControls);
  } else {
    initControls();
  }
}());
