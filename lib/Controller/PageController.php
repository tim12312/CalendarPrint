<?php	
namespace OCA\CalendarPrint\Controller;
use DateTimeImmutable;
use OCP\AppFramework\Http\DownloadResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\Calendar\IManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class PageController extends Controller {
	private $userId;
	private $calendarManager;


	public function __construct($AppName, IRequest $request, $UserId, IManager $calendarManager){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->calendarManager = $calendarManager;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		return new TemplateResponse('calendarprint', 'index');  // templates/index.php
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function print($year,$month) : DownloadResponse{
		$month_date= strtotime($year."-".$month."-01");
		$end=date('t',$month_date);
		$principal = 'principals/users/'.$this->userId;
        $calendars = $this->calendarManager->getCalendarsForPrincipal($principal);
		$query = $this->calendarManager->newQuery($principal);
		$query->setTimerangeStart(new DateTimeImmutable($year."-".$month."-01T10:00:00"));
		$query->setTimerangeEnd(new DateTimeImmutable($year."-".$month."-".$end."T10:00:00"));
		
		$objects = $this->calendarManager->searchForPrincipal($query);
		$pdf = new FPDF();
		$pdf->AddPage("l");
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,5,date('F - Y',$month_date),0);
		$pdf->SetFont('Arial','B',12);
	 	$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFillColor(224,235,255);
		$days=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
		for($i=0; $i<7;$i++){
			$pdf->Cell(40,8,$days[$i],1);
		}

		$pdf->SetFont('Arial','',8);
		$day=1;
		$offset=date('w',$month_date)-1;
		if($offset<0){ $offset=6;}
		$end+=1;
		$day-=$offset;
		
		for($i=0; $i<6;$i++){
			$pdf->Ln();
			for($j=0; $j<7;$j++){
				if($day>0 && $day<$end){
					$pdf->Cell(40,5,strval($day),1,0,'R');
				}else{
					$pdf->Cell(40,5,"",1,0,'R');
				}
					$day++;
			}
			for($x=0; $x<3;$x++){
				$day-=7;
				$pdf->Ln();
				for($j=0; $j<7;$j++){
					if($day>0 && $day<$end){
						$erg=[];
						foreach($objects as $ob){
							if($ob['objects'][0]['DTSTART'][0]->format('d') == ($day)){
								if($ob['objects'][0]['DTSTART'][0]->format('hi')==1200 &&$ob['objects'][0]['DTEND'][0]->format('hi')==1200){
										$erg[]=substr($ob['objects'][0]['SUMMARY'][0],0,25);
								}else{
									$erg[]=$ob['objects'][0]['DTSTART'][0]->format('h:i')." ".substr($ob['objects'][0]['SUMMARY'][0],0,20);
								
								}
							}elseif($ob['objects'][0]['DTSTART'][0]->format('d') < ($day) && $ob['objects'][0]['DTEND'][0]->format('d') > ($day)){
								$erg[]=substr($ob['objects'][0]['SUMMARY'][0],0,25);
							}elseif($ob['objects'][0]['DTEND'][0]->format('d') == ($day) &&$ob['objects'][0]['DTEND'][0]->format('hi')!=1200){
								$erg[]=substr($ob['objects'][0]['SUMMARY'][0],0,25);
							}		
						}
						$pdf->Cell(40,7,$erg[$x],0);
					
						}else{
							$pdf->Cell(40,7,'',0);

					}
					$day++;
				}
			}
		}
		for($i=0; $i<8;$i++){
			$pdf->line(10+(40*$i),30,10+(40*$i),184);
		}
		$pdf->line(10,184,10+(7*40),184);
		return $pdf->Output('I',"eins.pdf");
	
		}

}
