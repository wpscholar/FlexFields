<?php

namespace FlexFields\Fields;

/**
 * Class CheckboxGroupField
 *
 * @package FlexFields\Fields
 *
 * @property array $options
 */
class CheckboxGroupField extends Field {

	/**
	 * Sanitize field value
	 *
	 * @param array $value
	 *
	 * @return array
	 */
	public function sanitize( $value ) {
		return array_map( 'sanitize_text_field', $value );
	}

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		wp_enqueue_style( 'flex-fields' );

		$checkboxGroup = $this->renderTemplate( 'checkbox-group.php', [
			'name'    => $this->getData( 'name' ),
			'value'   => $this->value,
			'label'   => $this->getData( 'label' ),
			'options' => $this->_normalizeOptions( $this->options ),
			'atts'    => $this->getData( 'atts', [] ),
		] );

		return $this->fieldWrapper( 'checkbox-group', $checkboxGroup );

	}

	/**
	 * Get options
	 *
	 * @return array
	 */
	protected function _get_options() {
		$options = $this->getData( 'options', [] );
		$options = is_callable( $options ) ? $options( $this ) : $options;

		return apply_filters( __CLASS__ . ':options', $this->_normalizeOptions( $options ), $this );
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value
	 */
	protected function _set_value( $value ) {
		$this->_value = (array) $value;
	}

	/**
	 * Normalize options
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	protected function _normalizeOptions( array $options ) {

		foreach ( $options as $index => $data ) {

			$option = [
				'label' => '',
				'value' => '',
			];

			// If value is scalar, just normalize using that value
			if ( is_scalar( $data ) ) {
				$option = [
					'label' => $data,
					'value' => $data,
				];
			}

			// If value is an object, convert to an array
			if ( is_object( $data ) ) {
				$data = (array) $data;
			}

			// If value is an array, normalize alternative data structures
			if ( is_array( $data ) ) {
				$option['label'] = isset( $data['label'] ) ? $data['label'] : '';
				$option['value'] = isset( $data['value'] ) ? $data['value'] : '';
			}

			$options[ $index ] = (object) $option;

		}

		return $options;
	}

}
