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
                TD::make('date'),
                TD::make('time'),
                TD::make('instructor'),

                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Training_Schedule $t_schedule) {
                        return Button::make('Delete Schedule')
                            ->confirm('After deleting, the Schedule will be gone forever.')
                            ->method('delete', ['training_sched' => $t_schedule->id]);
                    }),
            ]),
            Layout::modal('trainingModal', Layout::rows([
                Input::make('t_schedule.name')
                    ->title('Name')
                    ->placeholder(''),
                Input::make('t_schedule.date')
                    ->type('date')
                    ->title('Date')
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
        't_schedule.date' => 'date_format',
        't_schedule.time' => 'date_format:H:i',
        't_schedule.instructor' => 'required|max:255',
    ]);

    $t_schedule = new Training_Schedule();
    $t_schedule->name = $request->input('t_schedule.name');
    $t_schedule->date = $request->input('t_schedule.date');
    $t_schedule->time = $request->input('t_schedule.time');
    $t_schedule->instructor = $request->input('t_schedule.instructor');
    $t_schedule->save();
    }
    public function delete(Training_Schedule $t_schedule)
    {
        $t_schedule->delete();
    }
}
