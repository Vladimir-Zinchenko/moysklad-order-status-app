document.addEventListener("DOMContentLoaded", function(event) {
  if (document.getElementById('orders-table') !== null) {
    loadOrders();
  }
  if (document.getElementById('login-from') !== null) {
    login();
  }
  if (document.getElementById('logout-btn') !== null) {
    const btn = document.getElementById('logout-btn');
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      fetch('/api/auth/logout')
        .then(response => response.json())
        .then(data => {
          window.location.href = '/';
        });
    });
  }

  function loadOrders() {
    const loader = document.getElementById('loader');
    loader.style.display = 'flex';
    fetch('/api/cutomerorder')
      .then(response => response.json())
      .then(data => {
        const tableBody = document.getElementById('orders-table')
          .querySelector('tbody');
        let tableContent = '';
        data.forEach(((rowData, idx) => tableContent += renderRow(rowData, idx)));
        tableBody.innerHTML = tableContent;
        loader.style.display = 'none';

        document.querySelectorAll('.state-dropdown').forEach((el)=>{
          let settings = {
            render: {
              option: function(data, escape) {
                return '<div><span class="state-color" style="background-color: ' + data.color + '"></span>' + escape(data.text) + '</div>';
              },
              item: function(data, escape) {
                return '<div class="state-selected-color" style="background-color: ' + data.color + '">' + escape(data.text) + '</div>';
              },
            },
            onChange: function(value) {
              const orderId = el.closest('tr').getAttribute('data-id');
              changeState(orderId, value);
            },
          };
          new TomSelect(el,settings);
        });
      })
  }

  function renderRow(rowData) {
    const sum = (rowData.sum / 90)
      .toLocaleString('ru', {minimumFractionDigits: 2, maximumFractionDigits: 2})
      .replace(',', '.');
    const state = renderStatesList(rowData.state);
    // const state = renderState(rowData.state);
    const created = dayjs(rowData.created).format('DD.MM.YYYY HH:mm');
    const updated = dayjs(rowData.updated).format('DD.MM.YYYY HH:mm');

    return`<tr data-id="${rowData.id}">
    <td><a href="${rowData.href}" target="_blank">${rowData.name}</a></td>
    <td>${created}</td>
    <td><a href="${rowData.agent.href}" target="_blank">${rowData.agent.name}</a></td>
    <td>${rowData.organization.name}</td>
    <td>${sum}</td>
    <td>${rowData.currency.name}</td>
    <td class="order-state">${state}</td>
    <td>${updated}</td>
</tr>`;
  }

  function changeState(orderId, stateId) {
    const loader = document.getElementById('loader');
    fetch('/api/cutomerorder', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json; charset=UTF-8'
      },
      body: JSON.stringify({ id: orderId, stateId })
    }).then(response => {
      loader.style.display = 'none';
    })
  }

  function login() {
    const form = document.getElementById('login-from');
    const submitBtn = form.querySelector('button');
    submitBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('/api/auth', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: JSON.stringify(Object.fromEntries(formData))
      })
        .then(response => {
          if (response.ok) {
            window.location.href = '/';
            return ;
          }
          const error = form.getElementsByClassName('error')[0];
          error.style.visibility = 'visible';
        });
    });
  }

  function renderStatesList(current) {
    let list = '<select class="state-dropdown">'
    for (const [, data] of Object.entries(mkOrderStatesList)) {
      let selected = data.id === current ? 'selected' : '';
      list += `<option ${selected} data-color="${data.color}" value="${data.id}">${data.name}</option>`;
    }
    list += '</select>';
    return list;
  }
});
