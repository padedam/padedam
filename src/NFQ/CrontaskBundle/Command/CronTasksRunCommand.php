<?php

namespace NFQ\CrontaskBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class CronTasksRunCommand  extends ContainerAwareCommand
{
    private $output;
    protected function configure()
    {
        $this
            ->setName('crontasks:run')
            ->setDescription('Runs Cron Tasks if needed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Starting email sending...</comment>');
        $this->output = $output;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $events = $em->getRepository('NFQAssistanceBundle:AssistanceEvent')->findLatest();
        if(empty($events)){
            return $output->writeln('<info>No requests found!</info>');
        }
        foreach ($events as $event) {

            $request = $event->getAssistanceRequest();
            $owner = $request->getOwner();
            $helper = $event->getUser();
            if($request->getStatus() != $request::STATUS_TAKEN){
                $output->writeln(sprintf('skipping assistance request <info>#%s</info> with status <info>%s</info> ', $request->getId(), $request->getStatus()));
                continue;
            }

            try {
                //send the email
                $output->writeln(sprintf('Sending email to assistance request <info>#%s</info> asker email <info>%s</info>, helper email <info>%s</info>', $request->getId(), $owner->getEmail(), $helper->getEmail()));
                $this->sendEmail($event);
                $output->writeln('<info>SUCCESS</info>');
            } catch (\Exception $e) {
                $output->writeln('<error>'.$e->getMessage().'</error>');
            }
        }
        $output->writeln('<comment>Done!</comment>');
    }
    private function runCommand($string)
    {
        // Split namespace and arguments
        $namespace = split(' ', $string)[0];
        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);
        // Send all output to the console
        $returnCode = $command->run($input, $this->output);
        return $returnCode != 0;
    }

    private function sendEmail($event)
    {
        $request = $event->getAssistanceRequest();
        $owner = $request->getOwner();
        $helper = $event->getUser();

        $message = \Swift_Message::newInstance()
            ->setSubject('Jums sutiko padeti')
            ->setFrom('info@padedam.lt')
            ->setTo($owner->getEmail())
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'Emails/assistance_offered.html.twig',
                    ['helper' => $helper, 'request'=>$request]
                ),
                'text/html'
            );
        $this->getContainer()->get('mailer')->send($message);

    }
}