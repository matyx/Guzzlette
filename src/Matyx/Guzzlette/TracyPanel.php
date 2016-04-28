<?php

namespace Matyx\Guzzlette;

use Latte\Engine;
use Tracy;

class TracyPanel implements Tracy\IBarPanel {
	/** @var  RequestStack */
	protected $requestStack;

	/** @var  Engine */
	protected $latteEngine;

	/** @var  string tempDir */
	protected $tempDir;

	public function __construct($tempDir, RequestStack $stack) {
		$this->requestStack = $stack;

		$this->tempDir = $tempDir;
	}

	public function getTab() {
		if(empty($this->requestStack->getRequests())) return false;

		return "<span title='Guzzlette'>
					<svg enable-background=\"new 0 0 200 200\" height=\"16px\" id=\"Layer_1\" version=\"1.1\" viewBox=\"0 0 200 200\" width=\"16px\" xml:space=\"preserve\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><g><path d=\"M117.474,52.424h7.931v6.173c0.609-0.039,1.223-0.063,1.839-0.063c15.609,0,28.483,11.945,29.95,27.179   c0.748-0.039,1.492-0.057,2.232-0.057c2.478,0,4.908,0.206,7.282,0.581V52.424h7.931c1.332,0,2.598-0.79,3.136-2.094   c0.542-1.308,0.206-2.757-0.737-3.7l-28.578-28.583c-0.613-0.613-1.468-0.996-2.403-0.996s-1.786,0.383-2.402,0.996L115.071,46.63   c-0.939,0.943-1.276,2.392-0.734,3.7C114.88,51.634,116.141,52.424,117.474,52.424z\" fill=\"#6bcbfd\"/><path d=\"M159.426,91.081c-2.644,0-5.227,0.262-7.729,0.745c0.135-1.049,0.213-2.116,0.213-3.2   c0-13.622-11.046-24.667-24.667-24.667c-4.986,0-9.617,1.481-13.494,4.022c-6.318-17.668-23.2-30.312-43.041-30.312   c-25.237,0-45.702,20.46-45.702,45.702c0,1.8,0.117,3.575,0.323,5.326c-8.519,0.755-15.202,7.902-15.202,16.62   c0,3.905,1.343,7.487,3.59,10.333C5.446,121.194,0,130.624,0,141.329c0,17.065,13.834,30.899,30.9,30.899h34.479l-15.025-15.024   c-2.53-2.526-3.281-6.301-1.91-9.61c1.368-3.306,4.568-5.442,8.146-5.442h2.505v-32.488c0-2.353,0.918-4.567,2.583-6.232   c1.669-1.67,3.88-2.587,6.237-2.587h34.514c2.356,0,4.571,0.917,6.237,2.587c1.665,1.661,2.583,3.88,2.583,6.232v32.488h2.505   c3.579,0,6.779,2.137,8.147,5.446c1.371,3.303,0.62,7.076-1.91,9.606l-15.025,15.024h54.458c22.406,0,40.574-18.164,40.574-40.573   C200,109.245,181.832,91.081,159.426,91.081z\" fill=\"#6bcbfd\"/><path d=\"M116.893,149.671c-0.539-1.305-1.804-2.095-3.137-2.095h-7.927v-37.913c0-0.868-0.337-1.736-0.999-2.398   c-0.659-0.663-1.527-0.996-2.399-0.996H67.917c-0.868,0-1.736,0.333-2.399,0.996c-0.667,0.662-0.996,1.53-0.996,2.398v37.913   h-7.931c-1.332,0-2.594,0.79-3.136,2.095c-0.539,1.308-0.206,2.757,0.737,3.699l28.583,28.582c0.613,0.613,1.464,0.996,2.399,0.996   c0.939,0,1.786-0.383,2.402-0.996l28.583-28.582C117.102,152.428,117.435,150.979,116.893,149.671z\" fill=\"#6bcbfd\"/></g></svg>
					HTTP " . ($this->requestStack->getTotalTime() ? sprintf(' / %0.1f ms', $this->requestStack->getTotalTime() * 1000) : '') . " (" . count($this->requestStack->getRequests()) . ")</span>";
	}

	public function getPanel() {
		$latte = $this->getLatteEngine();

		return $latte->renderToString(__DIR__ . '/TracyPanel.latte', [
			'requests' => $this->requestStack->getRequests(),
		]);
	}


	protected function getLatteEngine() {
		if(!isset($this->latteEngine)) {
			$this->latteEngine = new Engine;
			$this->latteEngine->setTempDirectory($this->tempDir);
		}

		return $this->latteEngine;
	}
}