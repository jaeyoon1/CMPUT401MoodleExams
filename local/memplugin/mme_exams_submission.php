<?php
	require_once "php-qrcode-detector-decoder/QrReader.php";


	/**
	 * Class for handling scanned submission for MME exams.	
 	 * @author Jaeyoon Kim
	 * @version 0.0.1
	 * @since 2016-03-18
	 */ 
	class MME_exam_submission {
	

		private $pdf = NULL; /**< .pdf file object*/
		private $img = NULL; /**< image file object converted from $pdf*/
		private $data = NULL;
		private $qrtext =""; /**< The string contained within the QRcode*/

		/**
		 * The constuctor.
		 * The constructor takes in 1 string corresponding to the raw bytes of the image.
		 * The image should be an .png image.
		 * Only the first page will be read if a multi-page pdf is submitted.
		 * This constructor will be changed to use database parameters instead at a later time.
		 * @param blob $data Raw blob of the image.
		 */
		public function __construct($data){
			$this->img = new Imagick();
			$this->data = $data;
			$this->img->readImageBlob($this->data)
			$this->crop_image();
			$this->read_QRcode();
		}

		/**
		 * Public method. 
		 * This method returns a string found within the scanned QRcode.
		 * If no QRcode is found, the method return a string "Could not read QRcode"
		 * @return str string of the QRcode data
		 */
		public function get_data(){
			if($this->qrtext == ""){
				return NULL;
			} else {
				return $this->qrtext;
			}
		}

		/**
		 * Private method. 
		 * This method returns the blob form of the image object.
		 * @return string Blob of the image object.
		 */
		private function get_image_blob(){
			return $this->img->getImageBlob();
		}

		/**
		 * Private method.
		 * The method crops the image object leaving the top left corner for faster QRcode reading.
		 * @return void
		 */
		private function crop_image(){
			$this->img->cropImage(200,200,0,0);
		}

		/**
		 * Private method.
		 * The method reads the QRcode within the image object and sets the resulting value to $this->qrtext.
		 * @return void
		 */
		private function read_QRcode(){
			$blob = $this->img->getImageBlob();
			$qrcode = new QrReader($blob,QrReader::SOURCE_TYPE_BLOB);
			$this->qrtext = $qrcode->text();
		}

		/**
		 * Private method.
		 * Unimplemented
		 * @return void
		 */
		// Deserialize data after reading QR.
		private function deserialize_data(){
			return;
		}

		/**
		 * Private method.
		 * Unimplemented
		 * @return void
		 */
		//upload parameters (exam#,page#) to a table that has the image_id(?) as the key.
		private function do_database_thing(){
			return;
		}

	}

?>

