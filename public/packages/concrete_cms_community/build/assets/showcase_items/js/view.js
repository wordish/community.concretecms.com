/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

import createShowcaseItem from './add.js';
import editShowcaseItem from './edit.js';
import removeShowcaseItem from './remove.js';

$(".edit-showcase-item").click(function (e) {
    e.preventDefault();
    editShowcaseItem($(this).data());
});

window.editShowcaseItem = editShowcaseItem;

$(".create-showcase-item").click(function (e) {
    e.preventDefault();
    createShowcaseItem();
});

window.createShowcaseItem = createShowcaseItem;

$(".remove-showcase-item").click(function (e) {
    e.preventDefault();
    removeShowcaseItem($(this).data());
});

window.removeShowcaseItem = removeShowcaseItem;