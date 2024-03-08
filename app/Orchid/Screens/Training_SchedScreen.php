<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use App\Models\Training_Schedule;
use Illuminate\Http\Request;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;


class Training_SchedScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'training_sched' => Training_Schedule::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Training Schedule';
    }

    public function description(): ?string
    {
        return "All schedules will be posted here";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Schedule')
            ->modal('trainingModal')
            ->method('create')
            ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('training_sched', [
                TD::make('name'),
                TD::make('start_date'),
                TD::make('end_date'),
                TD::make('time'),
                TD::make('instructor'),

                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Training_Schedule $training__schedules) {
                        return Button::make('Delete Schedule')
                            ->confirm('After deleting, the Schedule will be gone forever.')
                            ->method('delete', ['training_sched' => $training__schedules->id]);
                    }),
            ]),
            Layout::modal('trainingModal', Layout::rows([
                Input::make('t_schedule.name')
                    ->title('Name')
                    ->placeholder(''),
                Input::make('t_schedule.start_date')
                    ->type('date')
                    ->title('Start Date')
                    ->horizontal(),
                Input::make('t_schedule.end_date')
                    ->type('date')
                    ->title('End Date')
                    ->horizontal(),
                Input::make('t_schedule.time')
                    ->type('time')
                    ->title('Time')
                    ->horizontal(),
                Input::make('t_schedule.instructor')
                    ->title('Instructor')
                    ->placeholder(''),
            ]))
            ->title('Create Training Schedule')
            ->applyButton('Add Schedule'),
        ];
    }
    public function create(Request $request)
    {
    // Validate form data, save task to database, etc.
    $request->validate([
        't_schedule.name' => 'required|max:255',
        't_schedule.start_date' => 'date',
        't_schedule.end_date' => 'date',
        't_schedule.time' => 'date_format:H:i',
        't_schedule.instructor' => 'required|max:255',
    ]);

    $training__schedules = new Training_Schedule();
    $training__schedules->name = $request->input('t_schedule.name');
    $training__schedules->start_date = $request->input('t_schedule.start_date');
    $training__schedules->end_date = $request->input('t_schedule.end_date');
    $training__schedules->time = $request->input('t_schedule.time');
    $training__schedules->instructor = $request->input('t_schedule.instructor');
    $training__schedules->save();
    }

    public function delete(Training_Schedule $training__schedules)
    {
        $training__schedules->delete();
    }
}
