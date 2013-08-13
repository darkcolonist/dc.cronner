<div class="mine-layer" style="height: 500px">
  <!-- manually attach allowOverflow method to pane -->
  <div class="ui-layout-north">
    This is the north pane, closable, slidable and resizable

    <ul>
      <li>
        <ul>
          <li>one</li>
          <li>two</li>
          <li>three</li>
          <li>four</li>
          <li>five</li>
        </ul>
        Drop-Down <!-- put this below so IE and FF render the same! -->
      </li>
    </ul>

  </div>

  <!-- allowOverflow auto-attached by option: west__showOverflowOnHover = true -->
  <div class="ui-layout-west">
    This is the west pane, closable, slidable and resizable

    <ul>
      <li>
        <ul>
          <li>one</li>
          <li>two</li>
          <li>three</li>
          <li>four</li>
          <li>five</li>
        </ul>
        Pop-Up <!-- put this below so IE and FF render the same! -->
      </li>
    </ul>

    <p><button onclick="myLayout.close('west')">Close Me</button></p>

    <p><a href="#" onClick="showOptions(myLayout,'defaults.fxSettings_open');showOptions(myLayout,'west.fxSettings_close')">Show Options.Defaults</a></p>

  </div>

  <div class="ui-layout-south">
    This is the south pane, closable, slidable and resizable &nbsp;
    <button onclick="myLayout.toggle('north')">Toggle North Pane</button>
  </div>

  <div class="ui-layout-east">
    This is the east pane, closable, slidable and resizable

    <!-- attach allowOverflow method to this specific element -->
    <ul onmouseover="myLayout.allowOverflow(this)" onmouseout="myLayout.resetOverflow('east')">
      <li>
        <ul>
          <li>one</li>
          <li>two</li>
          <li>three</li>
          <li>four</li>
          <li>five</li>
        </ul>
        Pop-Up <!-- put this below so IE and FF render the same! -->
      </li>
    </ul>

    <p><button onclick="myLayout.close('east')">Close Me</button></p>

    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
  </div>

  <div class="ui-layout-center">
    This center pane auto-sizes to fit the space <i>between</i> the 'border-panes'
    <p>This layout was created with only <b>default options</b> - no customization</p>
    <p>Only the <b>applyDefaultStyles</b> option was enabled for <i>basic</i> formatting</p>
    <p>The Pop-Up and Drop-Down lists demonstrate the <b>allowOverflow()</b> utility</p>
    <p>The Close and Toggle buttons are examples of <b>custom buttons</b></p>
    <p><a href="http://layout.jquery-dev.net/demos.html">Go to the Demos page</a></p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
    <p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p><p>...</p>
  </div>
</div>
