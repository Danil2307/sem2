<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Questions;
use App\Entity\Answers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $adminRoles = ['ROLE_USER', 'ROLE_ADMIN',];

        $userRoles = ['ROLE_USER'];
        
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'mamama');
        $user->setEmail('maUser@mail.ru');
        $user->setName('maUser');
        $user->setRoles($userRoles);
        $user->setPassword($password);
        $user->setApiToken(Uuid::v1()->toRfc4122());

        $manager->persist($user);

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setEmail('maAdmin@mail.ru');
        $user->setName('maAdmin');
        $user->setRoles($adminRoles);
        $user->setPassword($password);
        $user->setApiToken(Uuid::v1()->toRfc4122());

        $manager->persist($user);

        for ($i = 0; $i < 30; $i++) {
            
            if($i % 2 == 0){
                $question = new Questions();
                $question->setTitle('Windows глючит');
                $question->setText('Windows в последнее время сильно тормозит, подскажите что можно сделать? Может какие-либо вирусы почистить');
                $question->setCategory("Техника");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $question->setDate($date);
                $question->setUser($user);
                $manager->persist($question);

                $answer = new Answers();
                $answer->setText("Может майнер подхватил, посмотри в диспетчере задач, что вызывает нагрузку.
а может обновление качает, это заметно когда один жесткий диск в в системе");
                $answer->setDate($date);
                $answer->setUser($user);
                $answer->setQuestion($question);
            }

            else if($i % 2 == 1){
                $question = new Questions();
                $question->setTitle('Болит голова');
                $question->setText('По утрам почему-то трещит голова, со здоровьем в целом всегда было все хорошо, но вот эта проблема не дает покоя, какие таблетки можно пропить, чтобы стало легче?');
                $question->setCategory("Болячки");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $question->setDate($date);
                $question->setUser($user);
                $question->setUser($user);
                $question->setFlag(true);
                $manager->persist($question);

                $answer = new Answers();
                $answer->setText("Ложите ладонь в ладонь на крест, там где большие пальцы раздвигаете ладонь, подносите ко тру, закрываете большими пальцами нос, и дышите через ладони считая при этом до 2о. нужно что бы дышалось тяжеловато. мне помогает когда мигрень :)");
                $answer->setDate($date);
                $answer->setUser($user);
                $answer->setQuestion($question);
            }

            if($i % 3 == 0){
                $flag = false;
            }
            else{
                $flag = true;
            }
            $answer->setFlag($flag);
            $manager->persist($answer);
        }
        $manager->flush();
    }
}
