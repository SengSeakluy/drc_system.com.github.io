<?php

namespace App\Widgets\ChartsWidgets;

use App\Charts\DailyRunBack as ChartsDailyRunBack;
use Arrilot\Widgets\AbstractWidget;

class DailyRunBack extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];
        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ];
        $runbackChart = new ChartsDailyRunBack;
        $runbackChart->minimalist(true);
        $runbackChart->labels(['Jan', 'Feb', 'Mar']);
        $runbackChart->dataset('Users by trimester', 'doughnut', [10, 25, 13])
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        return view('widgets.charts_widgets.daily_run_back', [
            'config' => $this->config,
            'runbackChart' => $runbackChart
        ]);
    }
}
