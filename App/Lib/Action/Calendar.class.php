<?php 
// ==================================================================
//
// 有关日期的一个类，方便创建日历，获取数据等
//
// ------------------------------------------------------------------
class calendar
{
	private $month;
	private $year;
	//包含所有天数的数组
	private $allDays = array();
	//空白天，即本月第一天是从星期几开始，前面就空几个位置
	private $blankDay;
	private $curDay;
	private $months = array('January','February','March','April','May','June','July','August','September','October','November','December');

	function __construct($month,$year)
	{
		$this->month = $month;
		$this->year = $year;
		$this->generateAllDay();
	}

	/**
	 * 得到给定年月的所有天数
	 * @return [type] [所有天数]
	 */
	private function generateAllDay()
	{
		//得到给定年份和月份的第一天和最后一天时间戳
		$firstDayUnix =  strtotime($this->month.' 1st '.$this->year);
		$lastDayUnix = strtotime($this->month.' '.date('t',$firstDayUnix).' '.$this->year);
		//生成该月所有天数的数组
		$this->allDays = range(1, date('t',$firstDayUnix));
		//得到该月第一天是星期几，则前面有几个空白
		$this->blankDay = date('N',$firstDayUnix);
		//得到当前是第几天
		$this->curDay = date('d');
	}

	/**
	 * 用户得到所有天数
	 * @return [type] [description]
	 */
	function getAllDay()
	{
		return $this->allDays;
	}

	/**
	 * 生成日历
	 * @return [type] [description]
	 */
	function generateCalendar()
	{
		$tmpArr = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		//将前面应该有几个空白天添加到数组中
		for($i=1;$i<(int)$this->blankDay;$i++)
		{
			$tmpArr[] = '';
		}
		//将天数插入数组
		foreach ($this->allDays as $value) {
			$tmpArr[] = $value;
		}

		$n = 1;
		$str = '<table class="calendarTable">';
		for ($i=0; $i < count($tmpArr) ; $i++) 
		{ 
			if($i%7 == 0)
			{
				$str .= '<tr>';
				for ($m=$i; $m < $n*7; $m++) 
				{ 
					if($tmpArr[$m] == $this->curDay)
					{
						$str .= '<td class="curDay">'.$tmpArr[$m].'</td>';
					}
					else
					{
						$str .= '<td>'.$tmpArr[$m].'</td>';
					}
				}
				$str .= '</tr>';
				$n++;
			}
		}
		$str .= '</table>';
		return $str;
	
	}


}
?>