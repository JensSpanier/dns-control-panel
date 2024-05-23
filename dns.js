(() => {
  const dom = {
    addDnsRecordButton: document.getElementById('addDnsRecord'),
    deleteDnsRecordButton: document.getElementById('deleteDnsRecord'),
    confirmDeleteDnsRecordButton: document.getElementById(
      'confirmDeleteDnsRecord'
    ),
    dnsRecordRows: document.querySelectorAll('.dns-record'),
    dnsRecordModal: document.getElementById('dnsRecordModal'),
    dnsRecordModalLabel: document.getElementById('dnsRecordModalLabel'),
    dnsRecordModalZone: document.getElementById('dnsRecordModalZone'),
    confirmDeleteModal: document.getElementById('confirmDeleteModal'),
    modalForm: document.getElementById('modalForm'),

    action: document.getElementById('action'),
    newHost: document.getElementById('newHost'),
    currentHost: document.getElementById('currentHost'),
    newType: document.getElementById('newType'),
    currentType: document.getElementById('currentType'),
    newTtl: document.getElementById('newTtl'),
    currentTtl: document.getElementById('currentTtl'),
    newData: document.getElementById('newData'),
    currentData: document.getElementById('currentData'),
  };

  const dnsRecordModal = new bootstrap.Modal(dom.dnsRecordModal);
  const confirmDeleteModal = new bootstrap.Modal(dom.confirmDeleteModal);
  const defaultTtl = dom.newTtl.value;

  dom.newHost.addEventListener('input', checkShowDotBefore);
  dom.newType.addEventListener('change', updateDataPlaceholder);

  dom.confirmDeleteModal.addEventListener('hide.bs.modal', () =>
    dnsRecordModal.show()
  );

  dom.deleteDnsRecordButton.addEventListener('click', () => {
    confirmDeleteModal.show();
    dnsRecordModal.hide();
  });

  dom.confirmDeleteDnsRecordButton.addEventListener('click', () => {
    dom.action.value = 'delete';
    dom.modalForm.submit();
  });

  function checkShowDotBefore() {
    dom.newHost.value
      ? dnsRecordModalZone.classList.add('show-dot-before')
      : dnsRecordModalZone.classList.remove('show-dot-before');
  }

  dom.addDnsRecordButton.addEventListener('click', () => {
    fillAndShowModal('add', {
      host: '',
      type: '',
      ttl: defaultTtl,
      data: '',
    });
  });

  dom.dnsRecordRows.forEach((row) => {
    row.addEventListener('click', () => {
      fillAndShowModal('update', {
        host: row.dataset.host,
        type: row.dataset.type,
        ttl: row.dataset.ttl,
        data: row.dataset.data,
      });
    });
  });

  function fillAndShowModal(action, values) {
    if (action === 'add') {
      dom.dnsRecordModalLabel.innerText = 'Add record';
      dom.deleteDnsRecordButton.hidden = true;
    }

    if (action === 'update') {
      dom.dnsRecordModalLabel.innerText = 'Update record';
      dom.deleteDnsRecordButton.hidden = false;
    }

    dom.action.value = action;
    dom.newHost.value = dom.currentHost.value = values.host;
    if (values.type) {
      dom.newType.value = dom.currentType.value = values.type;
    } else {
      dom.newType.selectedIndex = 0;
    }
    dom.newTtl.value = dom.currentTtl.value = values.ttl;
    dom.newData.value = dom.currentData.value = values.data;
    checkShowDotBefore();
    updateDataPlaceholder();
    dnsRecordModal.show();
  }

  function updateDataPlaceholder() {
    switch (dom.newType.value) {
      case 'A':
        dom.newData.placeholder = '203.0.113.195';
        break;
      case 'AAAA':
        dom.newData.placeholder = '2001:db8:85a3::8a2e:';
        break;
      case 'MX':
        dom.newData.placeholder = '10 mx.example.com';
        break;
      case 'TXT':
        dom.newData.placeholder = '"Example text"';
        break;
      case 'CNAME':
        dom.newData.placeholder = 'target.example.com';
        break;
      case 'SRV':
        dom.newData.placeholder = '0 5 9987 ts.example.com';
        break;
      case 'NS':
        dom.newData.placeholder = 'ns.example.com';
        break;
      case 'TLSA':
        dom.newData.placeholder =
          '3 1 1 69ce90113b586119f52ec8dbb3bdcda3d36c8785eed6c4a1ddd4230d54933464';
        break;
      case 'CAA':
        dom.newData.placeholder = '0 issue "letsencrypt.org"';
        break;
      case 'SOA':
        dom.newData.placeholder =
          'primary.server. email.adresse. serial refresh retry expire minimum';
        break;
      case 'PTR':
        dom.newData.placeholder = 'hostname.example.com';
        break;
      case 'DS':
        dom.newData.placeholder = '12345 8 2 0123456789abcdef';
        break;
    }
  }
})();
