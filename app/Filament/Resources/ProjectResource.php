<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category')
             ->schema([
                 Forms\Components\Select::make('category')
                     ->label('Category')
                     ->nullable()
                     ->required()
                     ->options([
                         'Creative direction' => 'Creative direction',
                         'Editorial' => 'Editorial',
                         'Music Videos' => 'Music Videos',
                         'Cover' => 'Cover',
                         'Advertising' => 'Advertising',
                         'Beauty' => 'Beauty',
                     ])
                     ->searchable(),
             ])
                    ->columnSpanFull(),

                Forms\Components\Section::make('Project information')
                    ->schema([


                        Forms\Components\TextInput::make('project_name')
                    ->nullable()
                    ->required()
                    ->maxLength(255)
                    ->label('Project Name'),



                        Forms\Components\TextInput::make('client')
                    ->nullable()
                    ->required()
                    ->maxLength(255)
                    ->label('Client'),

                        Forms\Components\DatePicker::make('data')
                    ->nullable()
                    ->required()
                    ->label('Date'),

                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Forms\Components\Section::make('Description')
            ->schema([
                Forms\Components\Textarea::make('title')
                    ->nullable()
                    ->required()
                    ->label('Title'),

                    Forms\Components\Textarea::make('description')
                        ->nullable()
                        ->required()
                        ->label('Description'),

            ])
            ->columnSpanFull(),

                Forms\Components\Section::make('Project videos')
                    ->schema([

                Forms\Components\FileUpload::make('video_upload')
                    ->label('Image / Video')
                    ->disk('api_public')
                    ->directory('videos')
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'video/mp4',
                        'video/webm',
                        'video/quicktime',
                    ])
                    ->maxSize(102400)
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),

            ])
            ->columns(1)
            ->columnSpanFull(),


                /*
                |--------------------------------------------------------------------------
                | Project Gallery — Bulk Upload + Drag & Drop
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Project Gallery')
                    ->schema([

                        Forms\Components\FileUpload::make('gallery_uploads')
                            ->label('آپلود گروهی تصاویر پروژه')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('api_public')
                            ->directory('images')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(5120)
                            ->maxFiles(100)
                            ->imagePreviewHeight('180')
                            ->panelLayout('grid')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')->sortable()->searchable()->label('Category'),
                TextColumn::make('project_name')->sortable()->searchable()->label('Project Name'),
                TextColumn::make('client')->label('Client'),
                TextColumn::make('director')->label('Director'),
                TextColumn::make('video_upload')
                    ->label('Video')
                    ->formatStateUsing(fn ($state) => $state ? '<a href="' . $state . '" target="_blank">View Video</a>' : '-')
                    ->html(),
                TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
