import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, SelectControl } from '@wordpress/components';
import { useEffect, useState } from 'react';
import axios from 'axios';
export default function Edit({ attributes, setAttributes }) {
	const [options, setOptions] = useState([])
	const blockProps = useBlockProps();

	const handlePostChange = (value) => {
		setAttributes({ selectedPost: parseInt(value) });
	};

	const fetchOptions = async () => {
		try {
			// Thực hiện truy vấn cơ sở dữ liệu để lấy dữ liệu từ bảng wp_yourdata.
			const response = await axios.get('https://quanyp.wpcomstaging.com/wp-json/your-plugin/v1/content');
			const data = response.data;

			// Xử lý dữ liệu và cập nhật options.
			const options = data.map(item => ({
				value: item.id,
				label: item.name, // Thay 'name' bằng trường bạn muốn hiển thị.
			}));
			setOptions(options)
		} catch (error) {
			console.error('Error fetching data:', error);
		}
	};

	// Fetch options khi component mount.
	useEffect(() => {
		fetchOptions();
	}, []);


	return (
		<>
			{/* <InspectorControls>
				<PanelBody title={__('Settings', 'copyright-date-block')}>
					<ToggleControl
						checked={showStartingYear}
						label={__(
							'Show starting year',
							'copyright-date-block'
						)}
						onChange={() =>
							setAttributes({
								showStartingYear: !showStartingYear,
							})
						}
					/>
					{showStartingYear && (
						<TextControl
							label={__(
								'Starting year',
								'copyright-date-block'
							)}
							value={startingYear}
							onChange={(value) =>
								setAttributes({ startingYear: value })
							}
						/>
					)}
				</PanelBody>
			</InspectorControls> */}
			<div {...blockProps}>
				<SelectControl
					label="Select Option"
					value={attributes.selectedPost}
					options={options}
					onChange={handlePostChange}
				/>
			</div>
		</>
	);

}