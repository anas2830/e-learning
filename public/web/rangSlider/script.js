const $element = $('input[type="range"]');
const $tooltip = $('#range-tooltip');
const sliderStates = [
  {name: "11", tooltip: "Slide up, and give assinment mark to your friend.", range: _.range(1, 33) },
  {name: "33", tooltip: "Don't give this, Minimum mark try to give up to 50%", range: _.range(33, 50)},
  {name: "50", tooltip: "Disappointing mark, Please re-check before giving mark, or you can send him a message.", range: _.range(50, 70)},
  {name: "70", tooltip: "Are you sure you are going to give this mark? It's just a mark try to give some more", range: _.range(70, 80)},
  {name: "80", tooltip: "Oh No, If he/she doing right job, please give him full mark & inspire.", range: _.range(80, 90)},
  {name: "90", tooltip: "Oho, Are you sure? Try to give full mark if she/he doing hard work.", range: _.range(90, 96)},
  {name: "95", tooltip: "Looks good!, Love other work and inspire them.", range: _.range(96, 100)},
  {name: "100", tooltip: "Great, you give 100% mark congrachulation!", range: [100] },
];
var currentState;
var $handle;

$element
  .rangeslider({
    polyfill: false,
    onInit: function() {
      $handle = $('.rangeslider__handle', this.$range);
      updateHandle($handle[0], this.value);
      updateState($handle[0], this.value);
    }
  })
  .on('input', function() {
    updateHandle($handle[0], this.value);
    checkState($handle[0], this.value);
  });

// Update the value inside the slider handle
function updateHandle(el, val) {
  el.textContent = val;
}

// Check if the slider state has changed
function checkState(el, val) {
  // if the value does not fall in the range of the current state, update that shit.
  if (!_.contains(currentState.range, parseInt(val))) {
    updateState(el, val);
  }
}

// Change the state of the slider
function updateState(el, val) {
  for (var j = 0; j < sliderStates.length; j++){
    if (_.contains(sliderStates[j].range, parseInt(val))) {
      currentState = sliderStates[j];
      // updateSlider();
    }
  }
  // If the state is high, update the handle count to read 50+
  if (currentState.name == "100") {
    updateHandle($handle[0], "100%");
  }
  // Update handle color
  $handle
    .removeClass (function (index, css) {
    return (css.match (/(^|\s)js-\S+/g) ||   []).join(' ');
  })
    .addClass("js-" + currentState.name);
  // Update tooltip
  $tooltip.html(currentState.tooltip);
}