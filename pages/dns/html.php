<?php
if (!defined('APPLICATION_CONTEXT')) exit;
$this->helperService->printStart('Edit zone');
$this->helperService->printNavbar($username, $zones);
$this->helperService->printContentStart();
?>

<?php if ($zones) : ?>

    <div class="row">
        <div class="col-auto">
            <h1><?= $zone ?></h1>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end mb-2 mb-sm-0">
                <button type="button" class="btn btn-success" id="addDnsRecord">
                    <i class="fa-solid fa-plus"></i>
                    Add
                </button>
            </div>
        </div>
    </div>

    <ul class="list-group">
        <?php foreach ($records as $record) : ?>
            <li class="list-group-item list-group-item-action dns-record" data-type="<?= $record['type'] ?>" data-ttl="<?= $record['ttl'] ?>" data-host="<?= htmlspecialchars(substr($record['host'], 0, - (strlen($zone) + 1))) ?>" data-data="<?= htmlspecialchars($record['data']) ?>">
                <div class="d-flex w-100 justify-content-between">
                    <p class="mb-0">
                        <?= htmlspecialchars($record['host']) ?>
                    </p>
                    <p class="text-body-secondary small mb-0"><?= $record['ttl'] ?></p>
                </div>
                <p class="mb-0 small text-truncate">
                    <kbd><?= $record['type'] ?></kbd>
                    <span class="font-monospace text-body-secondary">
                        <?= htmlspecialchars($record['data']) ?>
                    </span>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>

<?php else : ?>

    <p>No zones found.</p>

<?php endif; ?>

<div class="modal fade" id="dnsRecordModal" tabindex="-1" aria-labelledby="dnsRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="dnsRecordModalLabel">LABEL</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="modalForm">
                <input type="hidden" name="action" id="action">
                <div class="modal-body">
                    <input type="hidden" name="currentHost" id="currentHost">
                    <div class="input-group">
                        <input type="text" class="form-control border-bottom-0 rounded-bottom-0" name="newHost" id="newHost" placeholder="Host">
                        <span class="input-group-text border-bottom-0 rounded-bottom-0" id="dnsRecordModalZone"><?= $zone ?></span>
                    </div>
                    <input type="hidden" name="currentType" id="currentType">
                    <input type="hidden" name="currentTtl" id="currentTtl">
                    <div class="input-group">
                        <select class="form-select border-bottom-0 rounded-0" name="newType" id="newType">
                            <?php foreach ($recordTypes as $recordType) : ?>
                                <option><?= $recordType ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" required class="form-control border-bottom-0 rounded-0" name="newTtl" id="newTtl" placeholder="TTL" min="0">
                    </div>
                    <input type="hidden" name="currentData" id="currentData">
                    <input type="text" required class="form-control rounded-top-0" name="newData" id="newData">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteDnsRecord">
                        <i class="fa-solid fa-trash"></i>
                        Delete
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Confirm delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <p>Should the record really be deleted?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteDnsRecord">
                        <i class="fa-solid fa-trash"></i>
                        Delete
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $this->helperService->printContentEnd(); ?>

<script src="./dns.js?v=1"></script>

<?php $this->helperService->printEnd(); ?>