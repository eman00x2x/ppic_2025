<?php

namespace EO\View;

class DocumentRenderer extends Document {

	static function fetchHead($document) {
		$line_end = $document->getLineEnd();
		$tab = $document->getTab();

		if($document->canonical != "") {
			$html[] = '<link rel="canonical" href="' . $document->canonical . '" ' . $tagEnd . $line_end;
		}

		// Generate META tags (needs to happen as early as possible in the head)
		foreach ($document->metaTags as $type => $tag) {
			foreach ($tag as $name => $content) {
				if ($type == 'http-equiv') {
					$html[] = $tab . '<meta http-equiv="' . $name . '" content="' . $content . '" />' . $line_end;
				} elseif ($type == 'standard') {
					$html[] = $tab . '<meta name="' . $name . '" content="' . str_replace('"',"'",$content) . '" />' . $line_end;
				} elseif ($type == 'facebook') {
					$html[] = $tab . '<meta property="' . $name . '" content="' . str_replace('"',"'",$content) . '" />' . $line_end;
				}
			}
		}

		$html[] = $tab . '<meta name="description" content="' . $document->getDescription() . '" />' . $line_end;
		
		$html[] = $tab . '<title>' . htmlspecialchars($document->getTitle()) . '</title>' . $line_end;

		// Generate stylesheet links
		foreach ($document->styleSheets as $src => $attr ) {
			$html[] = $tab . '<link rel="stylesheet" href="' . $src . '" type="' . $attr['mime'] . '" media="' . $attr['media'] . '" />' . $line_end;
		}

		// Generate stylesheet declarations
		foreach ($document->style as $type => $content) {
			$html[] = $tab . '<style type="' . $type . '">' . $line_end;
			$html[] = $content . $line_end;
			$html[] = $tab . '</style>' . $line_end;
		}

		// Generate script file links
		foreach ($document->scripts as $src => $type) {
			$html[] = $tab . '<script type="' . $type . '" src="' . $src . '"></script>' . $line_end;
		}

		// Generate script declarations
		foreach ($document->script as $type => $content) {
			$html[] = $tab . '<script type="' . $type . '">' . $line_end;
			$html[] = $content . $line_end;
			$html[] = $tab . '</script>' . $line_end;
		}

		return implode("", $html);
	}

}
