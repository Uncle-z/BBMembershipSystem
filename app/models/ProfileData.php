<?php 

class ProfileData extends Eloquent {

    use \Laracasts\Presenter\PresentableTrait;

    protected $fillable = [
        'twitter', 'facebook', 'google_plus', 'github', 'irc', 'website', 'tagline', 'description', 'skills'
    ];

    protected $table = 'profile_data';

    protected $presenter = 'BB\Presenters\ProfileDataPresenter';

    public function setSkillsAttribute($skills)
    {
        if (is_array($skills)) {
            $this->attributes['skills_array'] = json_encode($skills);
        } else {
            $this->attributes['skills_array'] = json_encode([$skills]);
        }
    }


    public function getSkillsAttribute()
    {
        return (array)json_decode($this->skills_array);
    }
} 