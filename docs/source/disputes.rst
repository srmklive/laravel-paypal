:orphan:

Disputes
========

This package the following API endpoints for Disputes:

* Acknowledge Returned Items.
* Show Dispute Details.
* Partially Update Dispute.
* List Disputes.


Acknowledge Returned Item
-------------------------

This implementation creates an acknowledgement for the returned items by a customer by performing the following API call:

https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_acknowledge-return-item

.. code-block:: console

    $dispute_id = 'PP-D-27803';
    $dispute_note = 'Items have been returned by the customer';
    $acknowledgement_type = 'ITEM_RECEIVED';

    $dispute = $provider->acknowledgeItemReturned($dispute_id, $dispute_note, $acknowledgement_type);


Show Dispute Details
--------------------

This implementation gets details on a dispute by implementing the following API call:

https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_get

.. code-block:: console

    $dispute_id = 'PP-D-27803';

    $dispute = $provider->showDisputeDetails($dispute_id);


Partially Update Dispute
------------------------

This implementation performs a partial update on a dispute by implementing the following API call:

https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_patch

.. code-block:: console

    $data = json_decode("[
    {
        "op": "add",
        "path": "/partner_actions/-",
        "value": {
        "id": "AMX-22345",
        "name": "ACCEPT_DISPUTE",
        "create_time": "2018-01-12T10:41:35.000Z",
        "status": "PENDING"
        }
    }
    ]", true);

    $dispute_id = 'PP-D-27803';

    $dispute = $provider->updateDispute($dispute_id, $data);


List Disputes
-------------

This implementation gets details on all dispute by implementing the following API call:

https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_list

.. code-block:: console
   
    $disputes = $provider->listDisputes();